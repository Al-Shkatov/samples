<?php

class mail_service extends Module
{
    /**
     * @var CampaignModel
     */
    private $campaignModel;
    /**
     * @var SubscriberModel
     */
    private $subscriberModel;

    public function __construct()
    {
        parent::__construct();
        $this->campaignModel = new CampaignModel();
        $this->subscriberModel = new SubscriberModel();
    }

    public function index()
    {
        $page = $this->request->getParam('page');
        $this->view->campaigns = $this
            ->campaignModel
            ->filter(array('limit' => array('page' => $page)))
            ->all();
        $this->render('campaigns.php');
    }

    public function subscribers()
    {
        $page = $this->request->getParam('page');
        $this->view->subscribers = $this
            ->subscriberModel
            ->filter(array('limit' => array('page' => $page)))
            ->all();
        $this->render('subscribers.php');
    }

    public function addCampaign()
    {
        $this->render('campaign.php');
    }

    public function editCampaign()
    {
        $id = $this->request->getParam('id');
        if (null === $id) {
            Factory::getURI()->redirect('admin/mail_service/addCampaign');
        }
        $this->view->campaign = $this->campaignModel->get($id);
        $this->render('campaign.php');
    }

    public function saveCampaign()
    {
        $data = $this->request->getParam('campaign');
        $this->campaignModel->store($data);
        Factory::getURI()->redirect('admin/mail_service');
    }

    public function deleteCampaign()
    {
        Factory::getURI()->redirect('admin/mail_service');
    }

    public function addSubscriber()
    {
        $this->render('subscriber.php');
    }

    public function editSubscriber()
    {
        $id = $this->request->getParam('id');
        if (null === $id) {
            Factory::getURI()->redirect('admin/mail_service/addSubscriber');
        }
        $this->view->subscriber = $this->subscriberModel->get($id);
        $this->render('subscriber.php');
    }

    public function saveSubscriber()
    {
        $data = $this->request->getParam('subscriber');
        $this->subscriberModel->store($data);
        Factory::getURI()->redirect('admin/mail_service/subscribers');
    }

    public function deleteSubscriber()
    {
        Factory::getURI()->redirect('admin/mail_service/subscribers');
    }
}