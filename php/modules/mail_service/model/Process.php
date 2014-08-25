<?php


class ProcessModel extends Model
{
    const MAX_EMAIL_PER_CALL = 60;
    const TIME_TO_CALL = 60;
    const UNSUBSCRIBE_TMPL = '[unsubscribe]';
    const LINE_END = "\n";
    protected $_name = 'temporary_mails';
    private $campaignModel;
    private $subscriberModel;
    private $campaign;

    public function __construct($campaignId)
    {
        parent::__construct();
        $this->campaignModel = new CampaignModel();
        $this->subscriberModel = new SubscriberModel();
        $this->campaign = $this->campaignModel->get($campaignId);
        $this->campaign['html'] = html_entity_decode($this->campaign['html']);
    }

    public function init()
    {
        if(isset($_GET['d'])){
            $this->parseHtml();
            $contact = new stdClass();
            $contact->id = $contact->subscriber_id = 999;
            $contact->email = 'andriy.pasternak@barvinok.ua';
            $this->send($contact, $this->personalize($contact->subscriber_id));
            $contact = new stdClass();
            $contact->id = $contact->subscriber_id = 998;
            $contact->email = 'al.shkatov@gmail.com';
            $this->send($contact, $this->personalize($contact->subscriber_id));
            $contact = new stdClass();
            $contact->id = $contact->subscriber_id = 997;
            $contact->email = 'ingenertk@gmail.com';
            $this->send($contact, $this->personalize($contact->subscriber_id));
        }else{
            if ($this->campaign['status'] === 'unactive') {
                //@TODO handle error(cannot send unactive campaign)
            }
            if ($this->campaign['status'] === 'active') {
                $this->copy();
                $this->markCampaign();
            }
            if ($this->campaign['status'] === 'processing') {
                $this->parseHtml();
                $this->process();
            }
        }

    }

    private function copy()
    {
        $check = $this->where('campaign_id', $this->campaign['id'])->where('sent', '0')->limit(0, 1)->fetch(Model::FETCH_ROW);
        if (empty($check)) {
            $this->query('DELETE FROM ' . $this->db_pre . 'temporary_mails WHERE campaign_id=' . (int)$this->campaign['id']);
            $this->query('INSERT INTO ' . $this->db_pre . 'temporary_mails(subscriber_id,email,campaign_id)
               (SELECT id, email, ' . (int)$this->campaign['id'] . ' FROM ' . $this->db_pre . 'subscriber WHERE status="subscribed" GROUP BY email)');
        } else {
            //@TODO handle error(duplicate campaign send)
        }
    }

    private function markCampaign()
    {
        $this->campaign['status'] = 'processing';
        $this->campaignModel->save($this->campaign);
    }

    private function process()
    {
        $contacts = $this
            ->fields(array('t.*'))
            ->from('temporary_mails t')
            ->where('sent', '0')
            ->joinLeft('subscriber s', 's.id=t.subscriber_id')
            ->limit(0, self::MAX_EMAIL_PER_CALL)
            ->group('t.email')
            ->select()
            ->fetch(Model::FETCH_OBJECT);
        $delay = self::TIME_TO_CALL / self::MAX_EMAIL_PER_CALL;
        foreach ($contacts as $contact) {
            $this->send($contact, $this->personalize($contact->subscriber_id));
            sleep($delay);
        }
    }

    private function send($subscriber, $data)
    {
        $eol = self::LINE_END;
        $boundary = 'BRV-' . substr(md5(microtime(true)), 0, 16);
        $headers = 'MIME-Version: 1.0;' . $eol;
        $headers .= 'Date: ' . date("D, d M Y H:i:s O") . $eol;
        $headers .= 'From: Barvinok <subscribe@barvinok.ua>;' . $eol;
        $headers .= 'List-Unsubscribe: ' . $this->unsubscribeLink($subscriber->subscriber_id, $this->campaign['id'], 'empty') . $eol;
        $headers .= 'Return-Path: subscribe@barvinok.ua' . $eol;
        $headers .= 'Content-Type: multipart/alternative; boundary ="' . $boundary . '";' . $eol;
        $headers .= $eol . '--' . $boundary . $eol;
        $headers .= 'Content-Disposition: inline;' . $eol;
        $headers .= 'Content-Transfer-Encoding: 7bit;' . $eol;
        $headers .= 'Content-type: text/plain; charset=utf-8;' . $eol . $eol;
        $headers .= $data['text'];
        $headers .= $eol . '--' . $boundary . $eol;
        $headers .= 'Content-Disposition: inline;' . $eol;
        $headers .= 'Content-Transfer-Encoding: 7bit;' . $eol;
        $headers .= 'Content-type: text/html; charset=utf-8;' . $eol . $eol;
        $headers .= $data['html'];
        $headers .= $eol . '--' . $boundary . '--';
        mail($subscriber->email, '=?UTF-8?B?' . base64_encode($this->campaign['subject']) . '?=', '', $headers);
        if(isset($_GET['d'])){
            echo '<pre>';
            echo '=?UTF-8?B?' . base64_encode($this->campaign['subject']) . '?='."\r\n";
            echo $headers;
            echo '</pre>';

        }
        if(!isset($_GET['d'])){
            $this->save(array('sent' => 1, 'id' => $subscriber->id));
        }
    }

    private function parseHtml()
    {
        $view = new View();
        $stModel = Loader::loadModel('modules/store/model/Store');
        preg_match_all('/(?<product>\[product.*id=(?:"|&quot;)(?<id>\d*)(?:"|&quot;).*\])/iu', $this->campaign['html'], $products);
        foreach ($products['product'] as $key => $replacement) {
            $view->product = $stModel->getStore($products['id'][$key]);
            $this->campaign['html'] = str_replace($replacement,
                $view->renderFile(ROOT_DIR . '/modules/mail_service/views/partial/product.php'),
                $this->campaign['html']);
        }
        $this->campaign['html'] = str_replace('src="/', 'src="' . URI::base(true) . '/', $this->campaign['html']);
    }

    private function personalize($subscriber_id)
    {
        $html = $this->campaign['html'];
        $text = $this->campaign['text'];
        $pUnsubscribe = '/' . preg_quote(self::UNSUBSCRIBE_TMPL) . '/iu';
        if (preg_match($pUnsubscribe, $html)) {
            $html = preg_replace($pUnsubscribe, $this->unsubscribeLink($subscriber_id, $this->campaign['id']), $html);
        } else {
            $html .= '<br />' . $this->unsubscribeLink($subscriber_id, $this->campaign['id']);
        }
        if (preg_match($pUnsubscribe, $text)) {
            $text = preg_replace($pUnsubscribe, $this->unsubscribeLink($subscriber_id, $this->campaign['id']), $text, 'text');
        } else {
            $text .= self::LINE_END . $this->unsubscribeLink($subscriber_id, $this->campaign['id'], 'text') . self::LINE_END;
        }
        return array('html' => $html, 'text' => $text);
    }

    private function unsubscribeLink($subscriber_id, $campaign_id, $type = 'html')
    {

        $data = json_encode(array('subscriber_id' => $subscriber_id, 'campaign_id' => $campaign_id));
        $url = Factory::getURI()->base(true) . 'mail_service/unsubscribe/' . base64_encode($data);
        if ($type == 'text') {
            return '
            Це повідомлення надіслано Вам тому, що Ви підписались на розсилку новин на сайті [' . URI::base(true) . ']
            Щоб відписатись від розсилки, перейдіть за посиланням [' . $url . ']';
        }
        if ($type == 'empty') {
            return $url;
        }
        return 'Це повідомлення надіслано Вам тому, що Ви підписались на розсилку новин на сайті <a  style="font-size:11px;font-family:Arial, Geneva, sans-serif;color:#006230;" href="' . URI::base(true) . '">' . URI::base(true) . '</a><br/>
        Щоб відписатись від розсилки, натисніть <a style="font-size:11px;font-family:Arial, Geneva, sans-serif;color:#006230;" href="' . $url . '">наступне посилання</a>';
    }
} 