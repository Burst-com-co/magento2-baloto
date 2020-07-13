<?php
namespace Burst\Baloto\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class SalesPlaceAfter implements ObserverInterface
{
    //Order Variables
    private $order , $reference, $alt_reference, $payment, $payment_code, $total_amount, $tax_amount;
    //Customer Variables
    private $customer_id, $name, $last_name, $document, $mail, $phone;
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager, $Bbaloto;
    private $logger, $login, $trankey, $expiration;
    
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager, 
        \Psr\Log\LoggerInterface $logger,
        \Burst\Baloto\Helper\Payu $baloto,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Burst\Baloto\Helper\Config $config) {

        $this->_objectManager = $objectManager;
        $this->baloto = $baloto;
        $this->config = $config;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->login=$this->scopeConfig->getValue('payment/burst_baloto/login', ScopeInterface::SCOPE_STORE);
        $this->trankey=$this->scopeConfig->getValue('payment/burst_baloto/key', ScopeInterface::SCOPE_STORE);
        $this->expiration=$this->scopeConfig->getValue('payment/burst_baloto/expiration', ScopeInterface::SCOPE_STORE);
    }
    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->defineVariablesToLink($observer);
        $this->baloto->createLink($this->reference,  $this->mail, $this->total_amount, $this->name, 'BALOTO');
    }
    private function defineVariablesToLink($observer){
        //Order Data
        $this->order = $observer->getEvent()->getOrder();
        $this->payment = $this->order->getPayment();
        $this->payment_code=$this->payment->getMethodInstance()->getCode();
        $this->reference = $this->order->getIncrementId();
        $this->alt_reference = $this->order->getEntityId();
        $this->total_amount=ceil($this->order->getGrandTotal());
        $this->tax_amount=$this->order->getTaxAmount();
        //Customer Data
        $this->customer_id = $this->order->getCustomerId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customer = $objectManager->create('Magento\Customer\Model\Customer')->load($this->customer_id);
        $this->mail=$customer->getEmail();
        $this->name=$customer->getName();
        $this->document=$customer->getCedula();
        $this->lastname='';
        $this->phone = $observer->getOrder()->getBillingAddress()->getTelephone();
    }
}