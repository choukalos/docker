<?php

require_once dirname(__FILE__).'/../Client.php';
require_once dirname(__FILE__).'/../Cluster.php';
require_once dirname(__FILE__).'/../Sentinel.php';

class CredisSentinelTest extends PHPUnit_Framework_TestCase
{

  /** @var Credis_Sentinel */
  protected $sentinel;

  protected $sentinelConfig;
  protected $redisConfig;

  protected $useStandalone = FALSE;

  protected function setUp()
  {
    if($this->sentinelConfig === NULL) {
      $configFile = dirname(__FILE__).'/sentinel_config.json';
      if( ! file_exists($configFile) || ! ($config = file_get_contents($configFile))) {
        $this->markTestSkipped('Could not load '.$configFile);
        return;
      }
      $this->sentinelConfig = json_decode($config);
    }

    if($this->redisConfig === NULL) {
        $configFile = dirname(__FILE__).'/redis_config.json';
        if( ! file_exists($configFile) || ! ($config = file_get_contents($configFile))) {
            $this->markTestSkipped('Could not load '.$configFile);
            return;
        }
        $this->redisConfig = json_decode($config);
        $arrayConfig = array();
        foreach($this->redisConfig as $config) {
            $arrayConfig[] = (array)$config;
        }
        $this->redisConfig = $arrayConfig;
    }
    $sentinelClient = new Credis_Client($this->sentinelConfig->host, $this->sentinelConfig->port);
    $this->sentinel = new Credis_Sentinel($sentinelClient);
    if($this->useStandalone) {
      $this->sentinel->forceStandalone();
    } else if ( ! extension_loaded('redis')) {
      $this->fail('The Redis extension is not loaded.');
    }
  }
  protected function tearDown()
  {
    if($this->sentinel) {
      $this->sentinel = NULL;
    }
  }
  public function testMasterClient()
  {
      $master = $this->sentinel->getMasterClient($this->sentinelConfig->clustername);
      $this->assertInstanceOf('Credis_Client',$master);
      $this->assertEquals($this->redisConfig[0]['port'],$master->getPort());
      $this->setExpectedException('CredisException','Master not found');
      $this->sentinel->getMasterClient('non-existing-cluster');
  }
  public function testMasters()
  {
      $masters = $this->sentinel->masters();
      $this->assertInternalType('array',$masters);
      $this->assertCount(2,$masters);
      $this->assertArrayHasKey(0,$masters);
      $this->assertArrayHasKey(1,$masters);
      $this->assertArrayHasKey(1,$masters[0]);
      $this->assertArrayHasKey(1,$masters[1]);
      $this->assertArrayHasKey(5,$masters[1]);
      if($masters[0][1] == 'masterdown'){
          $this->assertEquals($this->sentinelConfig->clustername,$masters[1][1]);
          $this->assertEquals($this->redisConfig[0]['port'],$masters[1][5]);
      } else {
          $this->assertEquals('masterdown',$masters[1][1]);
          $this->assertEquals($this->sentinelConfig->clustername,$masters[0][1]);
          $this->assertEquals($this->redisConfig[0]['port'],$masters[0][5]);
      }
  }
  public function testMaster()
  {
      $master = $this->sentinel->master($this->sentinelConfig->clustername);
      $this->assertInternalType('array',$master);
      $this->assertArrayHasKey(1,$master);
      $this->assertArrayHasKey(5,$master);
      $this->assertEquals($this->sentinelConfig->clustername,$master[1]);
      $this->assertEquals($this->redisConfig[0]['port'],$master[5]);

      $this->setExpectedException('CredisException','No such master with that name');
      $this->sentinel->master('non-existing-cluster');
  }
  public function testSlaveClient()
  {
      $slaves = $this->sentinel->getSlaveClients($this->sentinelConfig->clustername);
      $this->assertInternalType('array',$slaves);
      $this->assertCount(1,$slaves);
      foreach($slaves as $slave){
          $this->assertInstanceOf('Credis_Client',$slave);
      }
      $this->setExpectedException('CredisException','No such master with that name');
      $this->sentinel->getSlaveClients('non-existing-cluster');
  }
  public function testSlaves()
  {
      $slaves = $this->sentinel->slaves($this->sentinelConfig->clustername);
      $this->assertInternalType('array',$slaves);
      $this->assertCount(1,$slaves);
      $this->assertArrayHasKey(0,$slaves);
      $this->assertArrayHasKey(5,$slaves[0]);
      $this->assertEquals(6385,$slaves[0][5]);

      $slaves = $this->sentinel->slaves('masterdown');
      $this->assertInternalType('array',$slaves);
      $this->assertCount(0,$slaves);

      $this->setExpectedException('CredisException','No such master with that name');
      $this->sentinel->slaves('non-existing-cluster');
  }
  public function testNonExistingClusterNameWhenCreatingSlaves()
  {
      $this->setExpectedException('CredisException','No such master with that name');
      $this->sentinel->createSlaveClients('non-existing-cluster');
  }
  public function testCreateCluster()
  {
      $cluster = $this->sentinel->createCluster($this->sentinelConfig->clustername);
      $this->assertInstanceOf('Credis_Cluster',$cluster);
      $this->assertCount(2,$cluster->clients());
      $cluster = $this->sentinel->createCluster($this->sentinelConfig->clustername,0,1,false);
      $this->assertInstanceOf('Credis_Cluster',$cluster);
      $this->assertCount(2,$cluster->clients());
      $this->setExpectedException('CredisException','The master is down');
      $this->sentinel->createCluster($this->sentinelConfig->downclustername);
  }
  public function testGetCluster()
  {
      $cluster = $this->sentinel->getCluster($this->sentinelConfig->clustername);
      $this->assertInstanceOf('Credis_Cluster',$cluster);
      $this->assertCount(2,$cluster->clients());
  }
  public function testGetClusterOnDbNumber2()
  {
      $cluster = $this->sentinel->getCluster($this->sentinelConfig->clustername,2);
      $this->assertInstanceOf('Credis_Cluster',$cluster);
      $this->assertCount(2,$cluster->clients());
      $clients = $cluster->clients();
      $this->assertEquals(2,$clients[0]->getSelectedDb());
      $this->assertEquals(2,$clients[1]->getSelectedDb());
  }
  public function testGetMasterAddressByName()
  {
      $address = $this->sentinel->getMasterAddressByName($this->sentinelConfig->clustername);
      $this->assertInternalType('array',$address);
      $this->assertCount(2,$address);
      $this->assertArrayHasKey(0,$address);
      $this->assertArrayHasKey(1,$address);
      $this->assertEquals($this->redisConfig[0]['host'],$address[0]);
      $this->assertEquals($this->redisConfig[0]['port'],$address[1]);
  }
  public function testPing()
  {
      $pong = $this->sentinel->ping();
      $this->assertEquals("PONG",$pong);
  }

  public function testGetHostAndPort()
  {
      $host = 'localhost';
      $port = '123456';

      $client = $this->getMock('\Credis_Client');
      $sentinel = new Credis_Sentinel($client);

      $client->expects($this->once())->method('getHost')->willReturn($host);
      $client->expects($this->once())->method('getPort')->willReturn($port);

      $this->assertEquals($host, $sentinel->getHost());
      $this->assertEquals($port, $sentinel->getPort());
  }
  public function testNonExistingMethod()
  {
      $this->setExpectedException('CredisException','Unknown sentinel subcommand \'bla\'');
      $this->sentinel->bla();
  }
}
