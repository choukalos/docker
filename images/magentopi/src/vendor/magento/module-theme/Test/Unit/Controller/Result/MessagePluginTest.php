<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Theme\Test\Unit\Controller\Result;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Message\Collection;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Message\MessageInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Element\Message\InterpretationStrategyInterface;
use Magento\Theme\Controller\Result\MessagePlugin;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class MessagePluginTest extends \PHPUnit_Framework_TestCase
{
    /** @var MessagePlugin */
    protected $model;

    /** @var CookieManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $cookieManagerMock;

    /** @var CookieMetadataFactory|\PHPUnit_Framework_MockObject_MockObject */
    protected $cookieMetadataFactoryMock;

    /** @var ManagerInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $managerMock;

    /** @var InterpretationStrategyInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $interpretationStrategyMock;

    /** @var Data|\PHPUnit_Framework_MockObject_MockObject */
    protected $dataMock;

    protected function setUp()
    {
        $this->cookieManagerMock = $this->getMockBuilder(CookieManagerInterface::class)
            ->getMockForAbstractClass();
        $this->cookieMetadataFactoryMock = $this->getMockBuilder(CookieMetadataFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->managerMock = $this->getMockBuilder(ManagerInterface::class)
            ->getMockForAbstractClass();
        $this->interpretationStrategyMock = $this->getMockBuilder(InterpretationStrategyInterface::class)
            ->getMockForAbstractClass();
        $this->dataMock = $this->getMockBuilder(Data::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->model = new MessagePlugin(
            $this->cookieManagerMock,
            $this->cookieMetadataFactoryMock,
            $this->managerMock,
            $this->interpretationStrategyMock,
            $this->dataMock
        );
    }

    public function testAfterRenderResultJson()
    {
        /** @var Json|\PHPUnit_Framework_MockObject_MockObject $resultMock */
        $resultMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cookieManagerMock->expects($this->never())
            ->method('setPublicCookie');

        $this->assertEquals($resultMock, $this->model->afterRenderResult($resultMock, $resultMock));
    }

    public function testAfterRenderResult()
    {
        
        $existingMessages = [
            [
                'type' => 'message0type',
                'text' => 'message0text',
            ],
        ];
        $messageType = 'message1type';
        $messageText = 'message1text';
        $messages = [
            [
                'type' => $messageType,
                'text' => $messageText,
            ],
        ];
        $messages = array_merge($existingMessages, $messages);
        
        /** @var Redirect|\PHPUnit_Framework_MockObject_MockObject $resultMock */
        $resultMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var PublicCookieMetadata|\PHPUnit_Framework_MockObject_MockObject $cookieMetadataMock */
        $cookieMetadataMock = $this->getMockBuilder(PublicCookieMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cookieMetadataFactoryMock->expects($this->once())
            ->method('createPublicCookieMetadata')
            ->willReturn($cookieMetadataMock);

        $this->cookieManagerMock->expects($this->once())
            ->method('setPublicCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode($messages),
                $cookieMetadataMock
            );
        $this->cookieManagerMock->expects($this->once())
            ->method('getCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode([])
            )
            ->willReturn(\Zend_Json::encode($existingMessages));

        $this->dataMock->expects($this->any())
            ->method('jsonDecode')
            ->willReturnCallback(
                function ($data) {
                    return \Zend_Json::decode($data);
                }
            );
        $this->dataMock->expects($this->any())
            ->method('jsonEncode')
            ->willReturnCallback(
                function ($data) {
                    return \Zend_Json::encode($data);
                }
            );

        /** @var MessageInterface|\PHPUnit_Framework_MockObject_MockObject $messageMock */
        $messageMock = $this->getMockBuilder(MessageInterface::class)
            ->getMock();
        $messageMock->expects($this->once())
            ->method('getType')
            ->willReturn($messageType);

        $this->interpretationStrategyMock->expects($this->once())
            ->method('interpret')
            ->with($messageMock)
            ->willReturn($messageText);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $collectionMock */
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$messageMock]);

        $this->managerMock->expects($this->once())
            ->method('getMessages')
            ->with(true, null)
            ->willReturn($collectionMock);

        $this->assertEquals($resultMock, $this->model->afterRenderResult($resultMock, $resultMock));
    }

    public function testAfterRenderResultWithoutExisting()
    {
        $messageType = 'message1type';
        $messageText = 'message1text';
        $messages = [
            [
                'type' => $messageType,
                'text' => $messageText,
            ],
        ];

        /** @var Redirect|\PHPUnit_Framework_MockObject_MockObject $resultMock */
        $resultMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var PublicCookieMetadata|\PHPUnit_Framework_MockObject_MockObject $cookieMetadataMock */
        $cookieMetadataMock = $this->getMockBuilder(PublicCookieMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cookieMetadataFactoryMock->expects($this->once())
            ->method('createPublicCookieMetadata')
            ->willReturn($cookieMetadataMock);

        $this->cookieManagerMock->expects($this->once())
            ->method('setPublicCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode($messages),
                $cookieMetadataMock
            );
        $this->cookieManagerMock->expects($this->once())
            ->method('getCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode([])
            )
            ->willReturn(\Zend_Json::encode([]));

        $this->dataMock->expects($this->any())
            ->method('jsonDecode')
            ->willReturnCallback(
                function ($data) {
                    return \Zend_Json::decode($data);
                }
            );
        $this->dataMock->expects($this->any())
            ->method('jsonEncode')
            ->willReturnCallback(
                function ($data) {
                    return \Zend_Json::encode($data);
                }
            );

        /** @var MessageInterface|\PHPUnit_Framework_MockObject_MockObject $messageMock */
        $messageMock = $this->getMockBuilder(MessageInterface::class)
            ->getMock();
        $messageMock->expects($this->once())
            ->method('getType')
            ->willReturn($messageType);

        $this->interpretationStrategyMock->expects($this->once())
            ->method('interpret')
            ->with($messageMock)
            ->willReturn($messageText);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $collectionMock */
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$messageMock]);

        $this->managerMock->expects($this->once())
            ->method('getMessages')
            ->with(true, null)
            ->willReturn($collectionMock);

        $this->assertEquals($resultMock, $this->model->afterRenderResult($resultMock, $resultMock));
    }

    public function testAfterRenderResultWithWrongJson()
    {
        $messageType = 'message1type';
        $messageText = 'message1text';
        $messages = [
            [
                'type' => $messageType,
                'text' => $messageText,
            ],
        ];

        /** @var Redirect|\PHPUnit_Framework_MockObject_MockObject $resultMock */
        $resultMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var PublicCookieMetadata|\PHPUnit_Framework_MockObject_MockObject $cookieMetadataMock */
        $cookieMetadataMock = $this->getMockBuilder(PublicCookieMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cookieMetadataFactoryMock->expects($this->once())
            ->method('createPublicCookieMetadata')
            ->willReturn($cookieMetadataMock);

        $this->cookieManagerMock->expects($this->once())
            ->method('setPublicCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode($messages),
                $cookieMetadataMock
            );
        $this->cookieManagerMock->expects($this->once())
            ->method('getCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode([])
            )
            ->willReturn(\Zend_Json::encode([]));

        $this->dataMock->expects($this->any())
            ->method('jsonDecode')
            ->willThrowException(new \Zend_Json_Exception);
        $this->dataMock->expects($this->any())
            ->method('jsonEncode')
            ->willReturnCallback(
                function ($data) {
                    return \Zend_Json::encode($data);
                }
            );

        /** @var MessageInterface|\PHPUnit_Framework_MockObject_MockObject $messageMock */
        $messageMock = $this->getMockBuilder(MessageInterface::class)
            ->getMock();
        $messageMock->expects($this->once())
            ->method('getType')
            ->willReturn($messageType);

        $this->interpretationStrategyMock->expects($this->once())
            ->method('interpret')
            ->with($messageMock)
            ->willReturn($messageText);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $collectionMock */
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$messageMock]);

        $this->managerMock->expects($this->once())
            ->method('getMessages')
            ->with(true, null)
            ->willReturn($collectionMock);

        $this->assertEquals($resultMock, $this->model->afterRenderResult($resultMock, $resultMock));
    }

    public function testAfterRenderResultWithWrongArray()
    {
        $messageType = 'message1type';
        $messageText = 'message1text';
        $messages = [
            [
                'type' => $messageType,
                'text' => $messageText,
            ],
        ];

        /** @var Redirect|\PHPUnit_Framework_MockObject_MockObject $resultMock */
        $resultMock = $this->getMockBuilder(Redirect::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var PublicCookieMetadata|\PHPUnit_Framework_MockObject_MockObject $cookieMetadataMock */
        $cookieMetadataMock = $this->getMockBuilder(PublicCookieMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cookieMetadataFactoryMock->expects($this->once())
            ->method('createPublicCookieMetadata')
            ->willReturn($cookieMetadataMock);

        $this->cookieManagerMock->expects($this->once())
            ->method('setPublicCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode($messages),
                $cookieMetadataMock
            );
        $this->cookieManagerMock->expects($this->once())
            ->method('getCookie')
            ->with(
                MessagePlugin::MESSAGES_COOKIES_NAME,
                \Zend_Json::encode([])
            )
            ->willReturn(\Zend_Json::encode('string'));

        $this->dataMock->expects($this->any())
            ->method('jsonDecode')
            ->willReturnCallback(
                function ($data) {
                    return \Zend_Json::decode($data);
                }
            );
        $this->dataMock->expects($this->any())
            ->method('jsonEncode')
            ->willReturnCallback(
                function ($data) {
                    return \Zend_Json::encode($data);
                }
            );

        /** @var MessageInterface|\PHPUnit_Framework_MockObject_MockObject $messageMock */
        $messageMock = $this->getMockBuilder(MessageInterface::class)
            ->getMock();
        $messageMock->expects($this->once())
            ->method('getType')
            ->willReturn($messageType);

        $this->interpretationStrategyMock->expects($this->once())
            ->method('interpret')
            ->with($messageMock)
            ->willReturn($messageText);

        /** @var Collection|\PHPUnit_Framework_MockObject_MockObject $collectionMock */
        $collectionMock = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $collectionMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$messageMock]);

        $this->managerMock->expects($this->once())
            ->method('getMessages')
            ->with(true, null)
            ->willReturn($collectionMock);

        $this->assertEquals($resultMock, $this->model->afterRenderResult($resultMock, $resultMock));
    }
}
