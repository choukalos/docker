<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Test\Unit\Model;

use Magento\Customer\Model\AuthenticationInterface;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AccountManagementTest extends \PHPUnit_Framework_TestCase
{
    /** @var AccountManagement */
    protected $accountManagement;

    /** @var ObjectManagerHelper */
    protected $objectManagerHelper;

    /** @var \Magento\Customer\Model\CustomerFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerFactory;

    /** @var \Magento\Framework\Event\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $manager;

    /** @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $storeManager;

    /** @var \Magento\Framework\Math\Random|\PHPUnit_Framework_MockObject_MockObject */
    protected $random;

    /** @var \Magento\Customer\Model\Metadata\Validator|\PHPUnit_Framework_MockObject_MockObject */
    protected $validator;

    /** @var \Magento\Customer\Api\Data\ValidationResultsInterfaceFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $validationResultsInterfaceFactory;

    /** @var \Magento\Customer\Api\AddressRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $addressRepository;

    /** @var \Magento\Customer\Api\CustomerMetadataInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerMetadata;

    /** @var \Magento\Customer\Model\CustomerRegistry|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerRegistry;

    /** @var \Psr\Log\LoggerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $logger;

    /** @var \Magento\Framework\Encryption\EncryptorInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $encryptor;

    /** @var \Magento\Customer\Model\Config\Share|\PHPUnit_Framework_MockObject_MockObject */
    protected $share;

    /** @var \Magento\Framework\Stdlib\StringUtils|\PHPUnit_Framework_MockObject_MockObject */
    protected $string;

    /** @var \Magento\Customer\Api\CustomerRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerRepository;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $scopeConfig;

    /** @var \Magento\Framework\Mail\Template\TransportBuilder|\PHPUnit_Framework_MockObject_MockObject */
    protected $transportBuilder;

    /** @var \Magento\Framework\Reflection\DataObjectProcessor|\PHPUnit_Framework_MockObject_MockObject */
    protected $dataObjectProcessor;

    /** @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject */
    protected $registry;

    /** @var \Magento\Customer\Helper\View|\PHPUnit_Framework_MockObject_MockObject */
    protected $customerViewHelper;

    /** @var \Magento\Framework\Stdlib\DateTime|\PHPUnit_Framework_MockObject_MockObject */
    protected $dateTime;

    /** @var \Magento\Customer\Model\Customer|\PHPUnit_Framework_MockObject_MockObject */
    protected $customer;

    /** @var \Magento\Framework\DataObjectFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $objectFactory;

    /** @var \Magento\Framework\Api\ExtensibleDataObjectConverter|\PHPUnit_Framework_MockObject_MockObject */
    protected $extensibleDataObjectConverter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Store\Model\Store
     */
    protected $store;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Customer\Model\Data\CustomerSecure
     */
    protected $customerSecure;

    /**
     * @var AuthenticationInterface |\PHPUnit_Framework_MockObject_MockObject
     */
    protected $authenticationMock;

    /**
     * @var EmailNotificationInterface |\PHPUnit_Framework_MockObject_MockObject
     */
    protected $emailNotificationMock;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function setUp()
    {
        $this->customerFactory = $this->getMock('Magento\Customer\Model\CustomerFactory', ['create'], [], '', false);
        $this->manager = $this->getMock('Magento\Framework\Event\ManagerInterface');
        $this->store = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManager = $this->getMock('Magento\Store\Model\StoreManagerInterface');
        $this->random = $this->getMock('Magento\Framework\Math\Random');
        $this->validator = $this->getMock('Magento\Customer\Model\Metadata\Validator', [], [], '', false);
        $this->validationResultsInterfaceFactory = $this->getMock(
            'Magento\Customer\Api\Data\ValidationResultsInterfaceFactory',
            [],
            [],
            '',
            false
        );
        $this->addressRepository = $this->getMock('Magento\Customer\Api\AddressRepositoryInterface');
        $this->customerMetadata = $this->getMock('Magento\Customer\Api\CustomerMetadataInterface');
        $this->customerRegistry = $this->getMock('Magento\Customer\Model\CustomerRegistry', [], [], '', false);
        $this->logger = $this->getMock('Psr\Log\LoggerInterface');
        $this->encryptor = $this->getMock('Magento\Framework\Encryption\EncryptorInterface');
        $this->share = $this->getMock('Magento\Customer\Model\Config\Share', [], [], '', false);
        $this->string = $this->getMock('Magento\Framework\Stdlib\StringUtils');
        $this->customerRepository = $this->getMock('Magento\Customer\Api\CustomerRepositoryInterface');
        $this->scopeConfig = $this->getMockBuilder('Magento\Framework\App\Config\ScopeConfigInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->transportBuilder = $this->getMock(
            'Magento\Framework\Mail\Template\TransportBuilder',
            [],
            [],
            '',
            false
        );
        $this->dataObjectProcessor = $this->getMock(
            'Magento\Framework\Reflection\DataObjectProcessor',
            [],
            [],
            '',
            false
        );
        $this->registry = $this->getMock('Magento\Framework\Registry');
        $this->customerViewHelper = $this->getMock('Magento\Customer\Helper\View', [], [], '', false);
        $this->dateTime = $this->getMock('Magento\Framework\Stdlib\DateTime');
        $this->customer = $this->getMock('Magento\Customer\Model\Customer', [], [], '', false);
        $this->objectFactory = $this->getMock('Magento\Framework\DataObjectFactory', [], [], '', false);
        $this->extensibleDataObjectConverter = $this->getMock(
            'Magento\Framework\Api\ExtensibleDataObjectConverter',
            [],
            [],
            '',
            false
        );
        $this->authenticationMock = $this->getMockBuilder(AuthenticationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->emailNotificationMock = $this->getMockBuilder(EmailNotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->accountManagement = $this->objectManagerHelper->getObject(
            'Magento\Customer\Model\AccountManagement',
            [
                'customerFactory' => $this->customerFactory,
                'eventManager' => $this->manager,
                'storeManager' => $this->storeManager,
                'mathRandom' => $this->random,
                'validator' => $this->validator,
                'validationResultsDataFactory' => $this->validationResultsInterfaceFactory,
                'addressRepository' => $this->addressRepository,
                'customerMetadataService' => $this->customerMetadata,
                'customerRegistry' => $this->customerRegistry,
                'logger' => $this->logger,
                'encryptor' => $this->encryptor,
                'configShare' => $this->share,
                'stringHelper' => $this->string,
                'customerRepository' => $this->customerRepository,
                'scopeConfig' => $this->scopeConfig,
                'transportBuilder' => $this->transportBuilder,
                'dataProcessor' => $this->dataObjectProcessor,
                'registry' => $this->registry,
                'customerViewHelper' => $this->customerViewHelper,
                'dateTime' => $this->dateTime,
                'customerModel' => $this->customer,
                'objectFactory' => $this->objectFactory,
                'extensibleDataObjectConverter' => $this->extensibleDataObjectConverter,
            ]
        );
        $reflection = new \ReflectionClass(get_class($this->accountManagement));
        $reflectionProperty = $reflection->getProperty('authentication');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->accountManagement, $this->authenticationMock);
        $reflectionProperty = $reflection->getProperty('emailNotification');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->accountManagement, $this->emailNotificationMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     */
    public function testCreateAccountWithPasswordHashWithExistingCustomer()
    {
        $websiteId = 1;
        $storeId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->once())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @expectedException \Magento\Framework\Exception\State\InputMismatchException
     */
    public function testCreateAccountWithPasswordHashWithCustomerWithoutStoreId()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer
            ->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer
            ->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $exception = new \Magento\Framework\Exception\AlreadyExistsException(
            new \Magento\Framework\Phrase('Exception message')
        );
        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customer, $hash)
            ->willThrowException($exception);

        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testCreateAccountWithPasswordHashWithLocalizedException()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer
            ->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer
            ->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $exception = new \Magento\Framework\Exception\LocalizedException(
            new \Magento\Framework\Phrase('Exception message')
        );
        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customer, $hash)
            ->willThrowException($exception);

        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testCreateAccountWithPasswordHashWithAddressException()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $address->expects($this->once())
            ->method('setCustomerId')
            ->with($customerId);
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->once())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer
            ->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer
            ->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share
            ->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager
            ->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customer, $hash)
            ->willReturn($customer);
        $exception = new \Magento\Framework\Exception\InputException(
            new \Magento\Framework\Phrase('Exception message')
        );
        $this->addressRepository
            ->expects($this->atLeastOnce())
            ->method('save')
            ->with($address)
            ->willThrowException($exception);
        $this->customerRepository
            ->expects($this->once())
            ->method('delete')
            ->with($customer);

        $this->accountManagement->createAccountWithPasswordHash($customer, $hash);
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testCreateAccountWithPasswordHashWithNewCustomerAndLocalizedException()
    {
        $storeId = 1;
        $storeName = 'store_name';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $customerMock = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMockForAbstractClass();

        $customerMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn(null);
        $customerMock->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customerMock->expects($this->once())
            ->method('setCreatedIn')
            ->with($storeName)
            ->willReturnSelf();
        $customerMock->expects($this->once())
            ->method('getAddresses')
            ->willReturn([]);
        $customerMock->expects($this->once())
            ->method('setAddresses')
            ->with(null)
            ->willReturnSelf();

        $storeMock = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();

        $storeMock->expects($this->once())
            ->method('getName')
            ->willReturn($storeName);

        $this->storeManager->expects($this->exactly(2))
            ->method('getStore')
            ->with($storeId)
            ->willReturn($storeMock);

        $exception = new \Magento\Framework\Exception\LocalizedException(
            new \Magento\Framework\Phrase('Exception message')
        );
        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customerMock, $hash)
            ->willThrowException($exception);

        $this->accountManagement->createAccountWithPasswordHash($customerMock, $hash);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testCreateAccountWithoutPassword()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $newLinkToken = '2jh43j5h2345jh23lh452h345hfuzasd96ofu';

        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $address->expects($this->once())
            ->method('setCustomerId')
            ->with($customerId);
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->atLeastOnce())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->atLeastOnce())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $this->customerRepository->expects($this->atLeastOnce())
            ->method('save')
            ->willReturn($customer);
        $this->addressRepository->expects($this->atLeastOnce())
            ->method('save')
            ->with($address);
        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->random->expects($this->once())
            ->method('getUniqueHash')
            ->willReturn($newLinkToken);
        $customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->disableOriginalConstructor()
            ->getMock();
        $customerSecure->expects($this->any())
            ->method('setRpToken')
            ->with($newLinkToken);
        $customerSecure->expects($this->any())
            ->method('setRpTokenCreatedAt');
        $customerSecure->expects($this->any())
            ->method('getPasswordHash')
            ->willReturn(null);
        $this->customerRegistry->expects($this->atLeastOnce())
            ->method('retrieveSecureData')
            ->willReturn($customerSecure);
        $this->emailNotificationMock->expects($this->once())
            ->method('newAccount')
            ->willReturnSelf();

        $this->accountManagement->createAccount($customer);
    }

    /**
     * Data provider for testCreateAccountWithPasswordInputException test
     *
     * @return array
     */
    public function dataProviderCheckPasswordStrength()
    {
        return [
            [
                'testNumber' => 1,
                'password' => 'qwer',
                'minPasswordLength' => 5,
                'minCharacterSetsNum' => 1
            ],
            [
                'testNumber' => 2,
                'password' => 'wrfewqedf1',
                'minPasswordLength' => 5,
                'minCharacterSetsNum' => 3
            ]
        ];
    }

    /**
     * @param int $testNumber
     * @param string $password
     * @param int $minPasswordLength
     * @param int $minCharacterSetsNum
     * @dataProvider dataProviderCheckPasswordStrength
     */
    public function testCreateAccountWithPasswordInputException(
        $testNumber,
        $password,
        $minPasswordLength,
        $minCharacterSetsNum
    ) {
        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->will(
                $this->returnValueMap(
                    [
                        [
                            AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH,
                            'default',
                            null,
                            $minPasswordLength,
                        ],
                        [
                            AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER,
                            'default',
                            null,
                            $minCharacterSetsNum],
                    ]
                )
            );

        $this->string->expects($this->any())
            ->method('strlen')
            ->with($password)
            ->willReturn(iconv_strlen($password, 'UTF-8'));

        if ($testNumber == 1) {
            $this->setExpectedException(
                '\Magento\Framework\Exception\InputException',
                'Please enter a password with at least ' . $minPasswordLength . ' characters.'
            );
        }

        if ($testNumber == 2) {
            $this->setExpectedException(
                '\Magento\Framework\Exception\InputException',
                'Minimum of different classes of characters in password is ' . $minCharacterSetsNum .
                '. Classes of characters: Lower Case, Upper Case, Digits, Special Characters.'
            );
        }

        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $this->accountManagement->createAccount($customer, $password);
    }

    public function testCreateAccountInputExceptionExtraLongPassword()
    {
        $password = '257*chars*************************************************************************************'
            . '****************************************************************************************************'
            . '***************************************************************';

        $this->string->expects($this->any())
            ->method('strlen')
            ->with($password)
            ->willReturn(iconv_strlen($password, 'UTF-8'));

        $this->setExpectedException(
            '\Magento\Framework\Exception\InputException',
            'Please enter a password with at most 256 characters.'
        );

        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $this->accountManagement->createAccount($customer, $password);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testCreateAccountWithPassword()
    {
        $websiteId = 1;
        $storeId = null;
        $defaultStoreId = 1;
        $customerId = 1;
        $customerEmail = 'email@email.com';
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';
        $newLinkToken = '2jh43j5h2345jh23lh452h345hfuzasd96ofu';
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';
        $password = 'wrfewqedf1';
        $minPasswordLength = 5;
        $minCharacterSetsNum = 2;

        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturnMap(
                [
                    [
                        AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH,
                        'default',
                        null,
                        $minPasswordLength,
                    ],
                    [
                        AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER,
                        'default',
                        null,
                        $minCharacterSetsNum],
                    [
                        AccountManagement::XML_PATH_REGISTER_EMAIL_TEMPLATE,
                        ScopeInterface::SCOPE_STORE,
                        $defaultStoreId,
                        $templateIdentifier,
                    ],
                    [
                        AccountManagement::XML_PATH_REGISTER_EMAIL_IDENTITY,
                        ScopeInterface::SCOPE_STORE,
                        1,
                        $sender
                    ]
                ]
            );
        $this->string->expects($this->any())
            ->method('strlen')
            ->with($password)
            ->willReturn(iconv_strlen($password, 'UTF-8'));
        $this->encryptor->expects($this->once())
            ->method('getHash')
            ->with($password, true)
            ->willReturn($hash);
        $address = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $address->expects($this->once())
            ->method('setCustomerId')
            ->with($customerId);
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($defaultStoreId);
        $website = $this->getMockBuilder('Magento\Store\Model\Website')->disableOriginalConstructor()->getMock();
        $website->expects($this->atLeastOnce())
            ->method('getStoreIds')
            ->willReturn([1, 2, 3]);
        $website->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($store);
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->atLeastOnce())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customer->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer->expects($this->once())
            ->method('setStoreId')
            ->with($defaultStoreId);
        $customer->expects($this->once())
            ->method('getAddresses')
            ->willReturn([$address]);
        $customer->expects($this->once())
            ->method('setAddresses')
            ->with(null);
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customerEmail)
            ->willReturn($customer);
        $this->share->expects($this->once())
            ->method('isWebsiteScope')
            ->willReturn(true);
        $this->storeManager->expects($this->atLeastOnce())
            ->method('getWebsite')
            ->with($websiteId)
            ->willReturn($website);
        $this->customerRepository->expects($this->atLeastOnce())
            ->method('save')
            ->willReturn($customer);
        $this->addressRepository->expects($this->atLeastOnce())
            ->method('save')
            ->with($address);
        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $this->random->expects($this->once())
            ->method('getUniqueHash')
            ->willReturn($newLinkToken);
        $customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->setMethods(['setRpToken', 'setRpTokenCreatedAt', 'getPasswordHash'])
            ->disableOriginalConstructor()
            ->getMock();
        $customerSecure->expects($this->any())
            ->method('setRpToken')
            ->with($newLinkToken);
        $customerSecure->expects($this->any())
            ->method('setRpTokenCreatedAt');
        $customerSecure->expects($this->any())
            ->method('getPasswordHash')
            ->willReturn($hash);
        $this->customerRegistry->expects($this->atLeastOnce())
            ->method('retrieveSecureData')
            ->willReturn($customerSecure);
        $this->emailNotificationMock->expects($this->once())
            ->method('newAccount')
            ->willReturnSelf();

        $this->accountManagement->createAccount($customer, $password);
    }

    /**
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function testSendPasswordReminderEmail()
    {
        $customerId = 1;
        $customerStoreId = 2;
        $customerEmail = 'email@email.com';
        $customerData = ['key' => 'value'];
        $customerName = 'Customer Name';
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();
        $customer->expects($this->any())
            ->method('getStoreId')
            ->willReturn($customerStoreId);
        $customer->expects($this->any())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->any())
            ->method('getEmail')
            ->willReturn($customerEmail);

        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn($customerStoreId);

        $this->storeManager->expects($this->at(0))
            ->method('getStore')
            ->willReturn($this->store);

        $this->storeManager->expects($this->at(1))
            ->method('getStore')
            ->with($customerStoreId)
            ->willReturn($this->store);

        $this->customerRegistry->expects($this->once())
            ->method('retrieveSecureData')
            ->with($customerId)
            ->willReturn($this->customerSecure);

        $this->dataObjectProcessor->expects($this->once())
            ->method('buildOutputDataArray')
            ->with($customer, '\Magento\Customer\Api\Data\CustomerInterface')
            ->willReturn($customerData);

        $this->customerViewHelper->expects($this->any())
            ->method('getCustomerName')
            ->with($customer)
            ->willReturn($customerName);

        $this->customerSecure->expects($this->once())
            ->method('addData')
            ->with($customerData)
            ->willReturnSelf();
        $this->customerSecure->expects($this->once())
            ->method('setData')
            ->with('name', $customerName)
            ->willReturnSelf();

        $this->scopeConfig->expects($this->at(0))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_REMIND_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE, $customerStoreId)
            ->willReturn($templateIdentifier);
        $this->scopeConfig->expects($this->at(1))
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_FORGOT_EMAIL_IDENTITY, ScopeInterface::SCOPE_STORE, $customerStoreId)
            ->willReturn($sender);

        $transport = $this->getMockBuilder('Magento\Framework\Mail\TransportInterface')
            ->getMock();

        $this->transportBuilder->expects($this->once())
            ->method('setTemplateIdentifier')
            ->with($templateIdentifier)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateOptions')
            ->with(['area' => Area::AREA_FRONTEND, 'store' => $customerStoreId])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setTemplateVars')
            ->with(['customer' => $this->customerSecure, 'store' => $this->store])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('setFrom')
            ->with($sender)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('addTo')
            ->with($customerEmail, $customerName)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->once())
            ->method('getTransport')
            ->willReturn($transport);

        $transport->expects($this->once())
            ->method('sendMessage');

        $this->assertEquals($this->accountManagement, $this->accountManagement->sendPasswordReminderEmail($customer));
    }

    /**
     * @param string $email
     * @param string $templateIdentifier
     * @param string $sender
     * @param int $storeId
     * @param int $customerId
     * @param string $hash
     */
    protected function prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash)
    {
        $websiteId = 1;

        $dateTime = date(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);

        $customerData = ['key' => 'value'];
        $customerName = 'Customer Name';

        $this->store->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $this->store->expects($this->any())
            ->method('getId')
            ->willReturn($storeId);

        $this->storeManager->expects($this->any())
            ->method('getStore')
            ->willReturn($this->store);

        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();
        $customer->expects($this->any())
            ->method('getEmail')
            ->willReturn($email);
        $customer->expects($this->any())
            ->method('getId')
            ->willReturn($customerId);
        $customer->expects($this->any())
            ->method('getStoreId')
            ->willReturn($storeId);

        $this->customerRepository->expects($this->once())
            ->method('get')
            ->with($email, $websiteId)
            ->willReturn($customer);
        $this->customerRepository->expects($this->once())
            ->method('save')
            ->with($customer)
            ->willReturnSelf();

        $this->random->expects($this->once())
            ->method('getUniqueHash')
            ->willReturn($hash);

        $this->customerViewHelper->expects($this->any())
            ->method('getCustomerName')
            ->with($customer)
            ->willReturn($customerName);

        $this->customerSecure->expects($this->any())
            ->method('setRpToken')
            ->with($hash)
            ->willReturnSelf();
        $this->customerSecure->expects($this->any())
            ->method('setRpTokenCreatedAt')
            ->with($dateTime)
            ->willReturnSelf();
        $this->customerSecure->expects($this->any())
            ->method('addData')
            ->with($customerData)
            ->willReturnSelf();
        $this->customerSecure->expects($this->any())
            ->method('setData')
            ->with('name', $customerName)
            ->willReturnSelf();

        $this->customerRegistry->expects($this->any())
            ->method('retrieveSecureData')
            ->with($customerId)
            ->willReturn($this->customerSecure);

        $this->dataObjectProcessor->expects($this->any())
            ->method('buildOutputDataArray')
            ->with($customer, '\Magento\Customer\Api\Data\CustomerInterface')
            ->willReturn($customerData);

        $this->prepareEmailSend($email, $templateIdentifier, $sender, $storeId, $customerName);
    }

    /**
     * @param $email
     * @param $templateIdentifier
     * @param $sender
     * @param $storeId
     * @param $customerName
     */
    protected function prepareEmailSend($email, $templateIdentifier, $sender, $storeId, $customerName)
    {
        $transport = $this->getMockBuilder('Magento\Framework\Mail\TransportInterface')
            ->getMock();

        $this->transportBuilder->expects($this->any())
            ->method('setTemplateIdentifier')
            ->with($templateIdentifier)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('setTemplateOptions')
            ->with(['area' => Area::AREA_FRONTEND, 'store' => $storeId])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('setTemplateVars')
            ->with(['customer' => $this->customerSecure, 'store' => $this->store])
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('setFrom')
            ->with($sender)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('addTo')
            ->with($email, $customerName)
            ->willReturnSelf();
        $this->transportBuilder->expects($this->any())
            ->method('getTransport')
            ->willReturn($transport);

        $transport->expects($this->any())
            ->method('sendMessage');
    }

    public function testInitiatePasswordResetEmailReminder()
    {
        $customerId = 1;

        $email = 'test@example.com';
        $template = AccountManagement::EMAIL_REMINDER;
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        $storeId = 1;

        mt_srand(mt_rand() + (100000000 * microtime()) % PHP_INT_MAX);
        $hash = md5(uniqid(microtime() . mt_rand(0, mt_getrandmax()), true));

        $this->emailNotificationMock->expects($this->once())
            ->method('passwordReminder')
            ->willReturnSelf();

        $this->prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash);

        $this->assertTrue($this->accountManagement->initiatePasswordReset($email, $template));
    }

    public function testInitiatePasswordResetEmailReset()
    {
        $storeId = 1;
        $customerId = 1;

        $email = 'test@example.com';
        $template = AccountManagement::EMAIL_RESET;
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        mt_srand(mt_rand() + (100000000 * microtime()) % PHP_INT_MAX);
        $hash = md5(uniqid(microtime() . mt_rand(0, mt_getrandmax()), true));

        $this->emailNotificationMock->expects($this->once())
            ->method('passwordResetConfirmation')
            ->willReturnSelf();

        $this->prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash);

        $this->assertTrue($this->accountManagement->initiatePasswordReset($email, $template));
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage Invalid value of "" provided for the email type field
     */
    public function testInitiatePasswordResetNoTemplate()
    {
        $storeId = 1;
        $customerId = 1;

        $email = 'test@example.com';
        $template = null;
        $templateIdentifier = 'Template Identifier';
        $sender = 'Sender';

        mt_srand(mt_rand() + (100000000 * microtime()) % PHP_INT_MAX);
        $hash = md5(uniqid(microtime() . mt_rand(0, mt_getrandmax()), true));

        $this->prepareInitiatePasswordReset($email, $templateIdentifier, $sender, $storeId, $customerId, $hash);

        $this->accountManagement->initiatePasswordReset($email, $template);
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage Invalid value of "" provided for the customerId field
     */
    public function testValidateResetPasswordTokenBadCustomerId()
    {
        $this->accountManagement->validateResetPasswordLinkToken(null, '');
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage resetPasswordLinkToken is a required field
     */
    public function testValidateResetPasswordTokenBadResetPasswordLinkToken()
    {
        $this->accountManagement->validateResetPasswordLinkToken(22, null);
    }

    /**
     * @expectedException \Magento\Framework\Exception\State\InputMismatchException
     * @expectedExceptionMessage Reset password token mismatch
     */
    public function testValidateResetPasswordTokenTokenMismatch()
    {
        $this->customerRegistry->expects($this->atLeastOnce())
            ->method('retrieveSecureData')
            ->willReturn($this->customerSecure);

        $this->accountManagement->validateResetPasswordLinkToken(22, 'newStringToken');
    }

    /**
     * @expectedException \Magento\Framework\Exception\State\ExpiredException
     * @expectedExceptionMessage Reset password token expired
     */
    public function testValidateResetPasswordTokenTokenExpired()
    {
        $this->reInitModel();
        $this->customerRegistry->expects($this->atLeastOnce())
            ->method('retrieveSecureData')
            ->willReturn($this->customerSecure);

        $this->accountManagement->validateResetPasswordLinkToken(22, 'newStringToken');
    }

    /**
     * return bool
     */
    public function testValidateResetPasswordToken()
    {
        $this->reInitModel();

        $this->customer
            ->expects($this->once())
            ->method('getResetPasswordLinkExpirationPeriod')
            ->willReturn(100000);

        $this->customerRegistry->expects($this->atLeastOnce())
            ->method('retrieveSecureData')
            ->willReturn($this->customerSecure);

        $this->assertTrue($this->accountManagement->validateResetPasswordLinkToken(22, 'newStringToken'));
    }

    /**
     * reInit $this->accountManagement object
     */
    private function reInitModel()
    {
        $this->customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->disableOriginalConstructor()
            ->setMethods(['getRpToken', 'getRpTokenCreatedAt'])
            ->getMock();

        $this->customerSecure
            ->expects($this->any())
            ->method('getRpToken')
            ->willReturn('newStringToken');

        $date = date('Y-m-d', strtotime('-1 year'));

        $this->customerSecure
            ->expects($this->any())
            ->method('getRpTokenCreatedAt')
            ->willReturn($date);

        $this->customer = $this->getMockBuilder('Magento\Customer\Model\Customer')
            ->disableOriginalConstructor()
            ->setMethods(['getResetPasswordLinkExpirationPeriod'])
            ->getMock();

        $this->objectManagerHelper = new ObjectManagerHelper($this);
        $this->accountManagement = $this->objectManagerHelper->getObject(
            'Magento\Customer\Model\AccountManagement',
            [
                'customerFactory' => $this->customerFactory,
                'customerRegistry' => $this->customerRegistry,
                'customerRepository' => $this->customerRepository,
                'customerModel' => $this->customer,
            ]
        );
        $reflection = new \ReflectionClass(get_class($this->accountManagement));
        $reflectionProperty = $reflection->getProperty('authentication');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->accountManagement, $this->authenticationMock);
    }

    /**
     * @return void
     */
    public function testChangePassword()
    {
        $customerId = 7;
        $email = 'test@example.com';
        $currentPassword = '1234567';
        $newPassword = 'abcdefg';
        $passwordHash = '1a2b3f4c';

        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();
        $customer->expects($this->any())
            ->method('getId')
            ->willReturn($customerId);

        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($email)
            ->willReturn($customer);

        $this->authenticationMock->expects($this->once())
            ->method('authenticate');

        $customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->setMethods(['setRpToken', 'setRpTokenCreatedAt', 'getPasswordHash'])
            ->disableOriginalConstructor()
            ->getMock();
        $customerSecure->expects($this->once())
            ->method('setRpToken')
            ->with(null);
        $customerSecure->expects($this->once())
            ->method('setRpTokenCreatedAt')
            ->with(null);
        $customerSecure->expects($this->any())
            ->method('getPasswordHash')
            ->willReturn($passwordHash);

        $this->customerRegistry->expects($this->any())
            ->method('retrieveSecureData')
            ->with($customerId)
            ->willReturn($customerSecure);

        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->willReturnMap(
                [
                    [
                        AccountManagement::XML_PATH_MINIMUM_PASSWORD_LENGTH,
                        'default',
                        null,
                        7,
                    ],
                    [
                        AccountManagement::XML_PATH_REQUIRED_CHARACTER_CLASSES_NUMBER,
                        'default',
                        null,
                        1
                    ],
                ]
            );
        $this->string->expects($this->any())
            ->method('strlen')
            ->with($newPassword)
            ->willReturn(7);

        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customer);

        $this->assertTrue($this->accountManagement->changePassword($email, $currentPassword, $newPassword));
    }

    /**
     * @return void
     */
    public function testChangePasswordException()
    {
        $email = 'test@example.com';
        $currentPassword = '1234567';
        $newPassword = 'abcdefg';

        $exception = new NoSuchEntityException(
            new \Magento\Framework\Phrase('Exception message')
        );
        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($email)
            ->willThrowException($exception);

        $this->setExpectedException(
            '\Magento\Framework\Exception\InvalidEmailOrPasswordException',
            'Invalid login or password.'
        );

        $this->accountManagement->changePassword($email, $currentPassword, $newPassword);
    }

    /**
     * @return void
     */
    public function testAuthenticate()
    {
        $username = 'login';
        $password = '1234567';
        $passwordHash = '1a2b3f4c';

        $customerData = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMock();

        $customerModel = $this->getMockBuilder('Magento\Customer\Model\Customer')
            ->disableOriginalConstructor()
            ->getMock();
        $customerModel->expects($this->once())
            ->method('updateData')
            ->willReturn($customerModel);

        $this->customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($username)
            ->willReturn($customerData);

        $this->authenticationMock->expects($this->once())
            ->method('authenticate');

        $customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->setMethods(['getPasswordHash'])
            ->disableOriginalConstructor()
            ->getMock();
        $customerSecure->expects($this->any())
            ->method('getPasswordHash')
            ->willReturn($passwordHash);

        $this->customerRegistry->expects($this->any())
            ->method('retrieveSecureData')
            ->willReturn($customerSecure);

        $this->customerFactory->expects($this->once())
            ->method('create')
            ->willReturn($customerModel);

        $this->manager->expects($this->exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                [
                    'customer_customer_authenticated',
                    ['model' => $customerModel, 'password' => $password]
                ],
                [
                    'customer_data_object_login', ['customer' => $customerData]
                ]
            );

        $this->assertEquals($customerData, $this->accountManagement->authenticate($username, $password));
    }

    /**
     * @param string|null $skipConfirmationIfEmail
     * @param int $isConfirmationRequired
     * @param string|null $confirmation
     * @param string $expected
     * @dataProvider dataProviderGetConfirmationStatus
     */
    public function testGetConfirmationStatus(
        $skipConfirmationIfEmail,
        $isConfirmationRequired,
        $confirmation,
        $expected
    ) {
        $websiteId = 1;
        $customerId = 1;
        $customerEmail = 'test1@example.com';

        $customerMock = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $customerMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);
        $customerMock->expects($this->any())
            ->method('getConfirmation')
            ->willReturn($confirmation);
        $customerMock->expects($this->any())
            ->method('getEmail')
            ->willReturn($customerEmail);
        $customerMock->expects($this->any())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $this->registry->expects($this->once())
            ->method('registry')
            ->with('skip_confirmation_if_email')
            ->willReturn($skipConfirmationIfEmail);

        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->with(AccountManagement::XML_PATH_IS_CONFIRM, ScopeInterface::SCOPE_WEBSITES, $websiteId)
            ->willReturn($isConfirmationRequired);

        $this->customerRepository->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customerMock);

        $this->assertEquals($expected, $this->accountManagement->getConfirmationStatus($customerId));
    }

    /**
     * @return array
     */
    public function dataProviderGetConfirmationStatus()
    {
        return [
            [null, 0, null, AccountManagement::ACCOUNT_CONFIRMATION_NOT_REQUIRED],
            ['test1@example.com', 0, null, AccountManagement::ACCOUNT_CONFIRMATION_NOT_REQUIRED],
            ['test2@example.com', 0, null, AccountManagement::ACCOUNT_CONFIRMATION_NOT_REQUIRED],
            ['test2@example.com', 1, null, AccountManagement::ACCOUNT_CONFIRMED],
            ['test2@example.com', 1, 'test', AccountManagement::ACCOUNT_CONFIRMATION_REQUIRED],
        ];
    }

    /**
     * @expectedException \Magento\Framework\Exception\LocalizedException
     */
    public function testCreateAccountWithPasswordHashForGuest()
    {
        $storeId = 1;
        $storeName = 'store_name';
        $websiteId = 1;
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        $storeMock = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $storeMock->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);
        $storeMock->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $storeMock->expects($this->once())
            ->method('getName')
            ->willReturn($storeName);

        $this->storeManager->expects($this->exactly(3))
            ->method('getStore')
            ->willReturn($storeMock);

        $customerMock = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')
            ->getMockForAbstractClass();
        $customerMock->expects($this->exactly(2))
            ->method('getId')
            ->willReturn(null);
        $customerMock->expects($this->exactly(3))
            ->method('getStoreId')
            ->willReturn(null);
        $customerMock->expects($this->exactly(2))
            ->method('getWebsiteId')
            ->willReturn(null);
        $customerMock->expects($this->once())
            ->method('setStoreId')
            ->with($storeId)
            ->willReturnSelf();
        $customerMock->expects($this->once())
            ->method('setWebsiteId')
            ->with($websiteId)
            ->willReturnSelf();
        $customerMock->expects($this->once())
            ->method('setCreatedIn')
            ->with($storeName)
            ->willReturnSelf();
        $customerMock->expects($this->once())
            ->method('getAddresses')
            ->willReturn(null);
        $customerMock->expects($this->once())
            ->method('setAddresses')
            ->with(null)
            ->willReturnSelf();

        $this->customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($customerMock, $hash)
            ->willThrowException(new \Magento\Framework\Exception\LocalizedException(__('Exception message')));

        $this->accountManagement->createAccountWithPasswordHash($customerMock, $hash);
    }

    public function testCreateAccountWithPasswordHashWithCustomerAddresses()
    {
        $websiteId = 1;
        $addressId = 2;
        $customerId = null;
        $storeId = 1;
        $hash = '4nj54lkj5jfi03j49f8bgujfgsd';

        //Handle store
        $store = $this->getMockBuilder('Magento\Store\Model\Store')->disableOriginalConstructor()->getMock();
        $store->expects($this->any())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        //Handle address - existing and non-existing. Non-Existing should return null when call getId method
        $existingAddress = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $nonExistingAddress = $this->getMockBuilder('Magento\Customer\Api\Data\AddressInterface')
            ->disableOriginalConstructor()
            ->getMock();
        //Ensure that existing address is not in use
        $this->addressRepository
            ->expects($this->atLeastOnce())
            ->method("save")
            ->withConsecutive(
                array($this->logicalNot($this->identicalTo($existingAddress))),
                array($this->identicalTo($nonExistingAddress))
            );

        $existingAddress
            ->expects($this->any())
            ->method("getId")
            ->willReturn($addressId);
        //Expects that id for existing address should be unset
        $existingAddress
            ->expects($this->once())
            ->method("setId")
            ->with(null);
        //Handle Customer calls
        $customer = $this->getMockBuilder('Magento\Customer\Api\Data\CustomerInterface')->getMock();
        $customer
            ->expects($this->atLeastOnce())
            ->method('getWebsiteId')
            ->willReturn($websiteId);
        $customer
            ->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);
        $customer
            ->expects($this->any())
            ->method("getId")
            ->willReturn($customerId);
        //Return Customer from customer repositoryå
        $this->customerRepository
            ->expects($this->atLeastOnce())
            ->method('save')
            ->willReturn($customer);
        $this->customerRepository
            ->expects($this->once())
            ->method('getById')
            ->with($customerId)
            ->willReturn($customer);
        $customerSecure = $this->getMockBuilder('Magento\Customer\Model\Data\CustomerSecure')
            ->setMethods(['setRpToken', 'setRpTokenCreatedAt', 'getPasswordHash'])
            ->disableOriginalConstructor()
            ->getMock();
        $customerSecure->expects($this->once())
            ->method('setRpToken')
            ->with($hash);

        $customerSecure->expects($this->any())
            ->method('getPasswordHash')
            ->willReturn($hash);

        $this->customerRegistry->expects($this->any())
            ->method('retrieveSecureData')
            ->with($customerId)
            ->willReturn($customerSecure);

        $this->random->expects($this->once())
            ->method('getUniqueHash')
            ->willReturn($hash);

        $customer
            ->expects($this->atLeastOnce())
            ->method('getAddresses')
            ->willReturn([$existingAddress, $nonExistingAddress]);

        $this->storeManager
            ->expects($this->atLeastOnce())
            ->method('getStore')
            ->willReturn($store);

        $this->assertSame($customer, $this->accountManagement->createAccountWithPasswordHash($customer, $hash));
    }
}
