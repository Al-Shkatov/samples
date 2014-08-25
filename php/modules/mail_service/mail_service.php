<?php

class mail_service extends Module
{
    public function form()
    {
        Factory::getDocument()->addStyle(Factory::getURI()->base(). 'public/css/jquery.fancybox.css');
        Factory::getDocument()->addScript(Factory::getURI()->base(). 'public/scripts/jquery.fancybox.pack.js');
        if($this->request->hasParam('email-subscribe')){
            $email = $this->request->getParam('email-subscribe');
            $sModel = new SubscriberModel();
            $sModel->store(array('email'=>$email));
            Flash::set('email_succ');
            Factory::getURI()->refresh();
        }
        $this->render('form.php');
    }

    public function unsubscribe()
    {
        $data = json_decode(base64_decode($this->request->getParam('data')));
        $sModel = new SubscriberModel();
        $sModel->upd(array('id'=>$data->subscriber_id,'status'=>'unsubscribed'));
        $this->render('unsubsribe.php');
    }

    public function start()
    {
        $cModel = new CampaignModel();
        $campaign = $cModel->getTodayCampaign();
        if ($campaign['id']) {
            $model = new ProcessModel($campaign['id']);
            $model->init();
        }
    }
}