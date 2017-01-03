<?php

require_once dirname(__FILE__).'/CredisTest.php';

class CredisStandaloneTest extends CredisTest
{

    protected $useStandalone = TRUE;

  public function testInvalidPersistentConnectionOnUnixSocket()
  {
      $this->credis->close();
      $this->credis = new Credis_Client('unix://'.realpath(__DIR__).'/redis.sock',0,null,'persistent');
      $this->credis->forceStandalone();
      $this->setExpectedException('CredisException','Persistent connections to UNIX sockets are not supported in standalone mode.');
      $this->credis->connect();
  }
  public function testPersistentConnectionsOnStandAloneTcpConnection()
  {
      $this->credis->close();
      $this->credis = new Credis_Client('tcp://'.$this->config[0]->host.':'.$this->config[0]->port.'/persistent');
      $this->credis->forceStandalone();
      $this->credis->set('key','value');
      $this->assertEquals('value',$this->credis->get('key'));
  }
    public function testPersistentvsNonPersistent() {}
    public function testStandAloneArgumentsExtra()
    {
        $this->assertTrue($this->credis->hMSet('hash', array('field1' => 'value1', 'field2' => 'value2'), 'field3', 'value3'));
        $this->assertEquals(array('value1','value2','value3'), $this->credis->hMGet('hash', array('field1','field2','field3')));
    }
    public function testStandAloneMultiPipelineThrowsException()
    {
        $this->setExpectedException('CredisException','A pipeline is already in use and only one pipeline is supported.');
        $this->credis->pipeline()->pipeline();
    }
}
