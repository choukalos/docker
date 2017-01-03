<?php

require_once dirname(__FILE__).'/../Client.php';
require_once dirname(__FILE__).'/../Cluster.php';

class CredisClusterTest extends PHPUnit_Framework_TestCase
{

  /** @var Credis_Cluster */
  protected $cluster;

  protected $config;

  protected $useStandalone = FALSE;

  protected function setUp()
  {
    if($this->config === NULL) {
      $configFile = dirname(__FILE__).'/redis_config.json';
      if( ! file_exists($configFile) || ! ($config = file_get_contents($configFile))) {
        $this->markTestSkipped('Could not load '.$configFile);
        return;
      }
      $this->config = json_decode($config);
      $arrayConfig = array();
      foreach($this->config as $config) {
          $arrayConfig[] = (array)$config;
      }
      $this->config = $arrayConfig;
      if(count($this->config) < 6) {
          $this->markTestSkipped('Config file '.$configFile.' should contain at least 6 entries');
          return;
      }
    }

    if($this->useStandalone && !extension_loaded('redis')) {
      $this->fail('The Redis extension is not loaded.');
    }
    $clients = array_slice($this->config,0,4);
    $this->cluster = new Credis_Cluster($clients,2,$this->useStandalone);
  }

  protected function tearDown()
  {
    if($this->cluster) {
      foreach($this->cluster->clients() as $client){
        if($client->isConnected()) {
            $client->flushAll();
            $client->close();
        }
      }
      $this->cluster = NULL;
    }
  }

  public function testKeyHashing()
  {
      $this->tearDown();
      $this->cluster = new Credis_Cluster(array_slice($this->config,0,3),2,$this->useStandalone);
      $keys = array();
      $lines = explode("\n", file_get_contents("keys.test"));
      foreach ($lines as $line) {
          $pair = explode(':', trim($line));
          if (count($pair) >= 2) {
              $keys[$pair[0]] = $pair[1];
          }
      }
      foreach ($keys as $key => $value) {
          $this->assertTrue($this->cluster->set($key, $value));
      }
      $this->cluster = new Credis_Cluster(array_slice($this->config,0,4),2,true,$this->useStandalone);
      $hits = 0;
      foreach ($keys as $key => $value) {
          if ($this->cluster->all('get',$key)) {
              $hits++;
          }
      }
      $this->assertEquals(count($keys),$hits);
  }
  public function testAlias()
  {
      $slicedConfig = array_slice($this->config,0,4);
      foreach($slicedConfig as $config) {
          $this->assertEquals($config['port'],$this->cluster->client($config['alias'])->getPort());
      }
      foreach($slicedConfig as $offset => $config) {
          $this->assertEquals($config['port'],$this->cluster->client($offset)->getPort());
      }
      $alias = "non-existent-alias";
      $this->setExpectedException('CredisException',"Client $alias does not exist.");
      $this->cluster->client($alias);
  }
  public function testMasterSlave()
  {
      $this->tearDown();
      $this->cluster = new Credis_Cluster(array($this->config[0],$this->config[6]),2,$this->useStandalone);
      $this->assertTrue($this->cluster->client('master')->set('key','value'));
      $this->assertEquals('value',$this->cluster->client('slave')->get('key'));
      $this->assertEquals('value',$this->cluster->get('key'));
      $this->assertFalse($this->cluster->client('slave')->set('key2','value'));

      $this->tearDown();
      $writeOnlyConfig = $this->config[0];
      $writeOnlyConfig['write_only'] = true;
      $this->cluster = new Credis_Cluster(array($writeOnlyConfig,$this->config[6]),2,$this->useStandalone);
      $this->assertTrue($this->cluster->client('master')->set('key','value'));
      $this->assertEquals('value',$this->cluster->client('slave')->get('key'));
      $this->assertFalse($this->cluster->client('slave')->set('key2','value'));
      $this->assertEquals('value',$this->cluster->get('key'));
  }
  public function testMasterWithoutSlavesAndWriteOnlyFlag()
  {
      $this->tearDown();
      $writeOnlyConfig = $this->config[0];
      $writeOnlyConfig['write_only'] = true;
      $this->cluster = new Credis_Cluster(array($writeOnlyConfig),2,$this->useStandalone);
      $this->assertTrue($this->cluster->set('key','value'));
      $this->assertEquals('value',$this->cluster->get('key'));
  }
  public function testDontHashForCodeCoverage()
  {
    $this->assertInternalType('array',$this->cluster->info());
  }
  public function testByHash()
  {
      $this->cluster->set('key','value');
      $this->assertEquals(6379,$this->cluster->byHash('key')->getPort());
  }
  public function testRwsplit()
  {
    $readOnlyCommands = array(
        'EXISTS',
        'TYPE',
        'KEYS',
        'SCAN',
        'RANDOMKEY',
        'TTL',
        'GET',
        'MGET',
        'SUBSTR',
        'STRLEN',
        'GETRANGE',
        'GETBIT',
        'LLEN',
        'LRANGE',
        'LINDEX',
        'SCARD',
        'SISMEMBER',
        'SINTER',
        'SUNION',
        'SDIFF',
        'SMEMBERS',
        'SSCAN',
        'SRANDMEMBER',
        'ZRANGE',
        'ZREVRANGE',
        'ZRANGEBYSCORE',
        'ZREVRANGEBYSCORE',
        'ZCARD',
        'ZSCORE',
        'ZCOUNT',
        'ZRANK',
        'ZREVRANK',
        'ZSCAN',
        'HGET',
        'HMGET',
        'HEXISTS',
        'HLEN',
        'HKEYS',
        'HVALS',
        'HGETALL',
        'HSCAN',
        'PING',
        'AUTH',
        'SELECT',
        'ECHO',
        'QUIT',
        'OBJECT',
        'BITCOUNT',
        'TIME',
        'SORT'
    );
    foreach($readOnlyCommands as $command){
        $this->assertTrue($this->cluster->isReadOnlyCommand($command));
    }
    $this->assertFalse($this->cluster->isReadOnlyCommand("SET"));
    $this->assertFalse($this->cluster->isReadOnlyCommand("HDEL"));
    $this->assertFalse($this->cluster->isReadOnlyCommand("RPUSH"));
    $this->assertFalse($this->cluster->isReadOnlyCommand("SMOVE"));
    $this->assertFalse($this->cluster->isReadOnlyCommand("ZADD"));
  }
  public function testCredisClientInstancesInConstructor()
  {
      $this->tearDown();
      $two = new Credis_Client($this->config[1]['host'],$this->config[1]['port']);
      $three = new Credis_Client($this->config[2]['host'],$this->config[2]['port']);
      $four = new Credis_Client($this->config[3]['host'],$this->config[3]['port']);
      $this->cluster = new Credis_Cluster(array($two,$three,$four),2,$this->useStandalone);
      $this->assertTrue($this->cluster->set('key','value'));
      $this->assertEquals('value',$this->cluster->get('key'));
      $this->setExpectedException('CredisException','Server should either be an array or an instance of Credis_Client');
      new Credis_Cluster(array(new stdClass()),2,$this->useStandalone);
  }
  public function testSetMasterClient()
  {
      $this->tearDown();
      $master = new Credis_Client($this->config[0]['host'],$this->config[0]['port']);
      $slave = new Credis_Client($this->config[6]['host'],$this->config[6]['port']);

      $this->cluster = new Credis_Cluster(array($slave),2,$this->useStandalone);
      $this->assertInstanceOf('Credis_Cluster',$this->cluster->setMasterClient($master));
      $this->assertCount(2,$this->cluster->clients());
      $this->assertEquals($this->config[6]['port'],$this->cluster->client(0)->getPort());
      $this->assertEquals($this->config[0]['port'],$this->cluster->client('master')->getPort());

      $this->cluster = new Credis_Cluster(array($this->config[0]),2,$this->useStandalone);
      $this->assertInstanceOf('Credis_Cluster',$this->cluster->setMasterClient(new Credis_Client($this->config[1]['host'],$this->config[1]['port'])));
      $this->assertEquals($this->config[0]['port'],$this->cluster->client('master')->getPort());

      $this->cluster = new Credis_Cluster(array($slave),2,$this->useStandalone);
      $this->assertInstanceOf('Credis_Cluster',$this->cluster->setMasterClient($master,true));
      $this->assertCount(1,$this->cluster->clients());
  }
}
