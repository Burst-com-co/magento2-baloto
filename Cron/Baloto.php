<?php
namespace Burst\Baloto\Cron;

class Baloto
{
    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Burst\Baloto\Model\BalotoFactory $paymentLinkFactory,
        \Burst\Baloto\Helper\Payu $Payu) {
        $this->logger = $logger;
        $this->Payu=$Payu;
        $this->paymentLinkFactory=$paymentLinkFactory;
    }

    public function execute(){
        $model = $this->paymentLinkFactory->create();
        $collection = $model->getCollection()
            ->addFieldToFilter('valid_until', ['gteq' => date('Y-m-d H:i:s')])
            ->addFieldToFilter('status', ['eq' => 'CREATED']);
        foreach($collection as $item){
            $data=$item->getData();
            $payment_status=$this->Payu->getStatus($data['order_id']);
            if (!is_null($payment_status) && $data["id"]!=$payment_status) {
                $update = $model->load($data["id"]);
                $update->setStatus($payment_status);
                $update->save();
            }
        }
    }

}
