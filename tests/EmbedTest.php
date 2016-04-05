<?php

use Cohensive\Embed\Embed;

class EmbedTest extends PHPUnit_Framework_TestCase {

    protected $embed;

    public function setUp()
    {
        $providers = require('src/config/config.php');
        $this->embed = new Embed();
        $this->embed->setProviders($providers['providers']);
    }

    public function testEmbedBasicConstructor()
    {
        $embed = new Embed('http://youtu.be/dQw4w9WgXcQ', array(
            'params' => array(
                'width' => 100
            )
        ));

        $this->assertInstanceOf('Cohensive\Embed\Embed', $embed);
        $this->assertEquals('http://youtu.be/dQw4w9WgXcQ', $embed->getUrl());
        $this->assertEquals(['width' => 100], $embed->getParams());
    }


    public function testEmbedUrlSetting()
    {
        $embed = new Embed('http://youtu.be/dQw4w9WgXcQ');

        $embed->setUrl('http://youtube.com/watch?v=QH2-TGUlwu4');
        $this->assertEquals('http://youtube.com/watch?v=QH2-TGUlwu4', $embed->getUrl());
    }


    public function testEmbedParamAndAttributeSetting()
    {
        $embed = new Embed('http://youtu.be/dQw4w9WgXcQ', array(
            'params' => array(
                'width' => 100
            )
        ));
        $embed->setAttribute('height', 100);
        $embed->setParam('width', 200);

        $this->assertEquals(['height' => 100], $embed->getAttributes());
        $this->assertEquals(['width' => 200], $embed->getParams());
    }


    public function testEmbedProviderSetting()
    {
        $embed = new Embed();
        $providers = require('src/config/config.php');
        $embed->setProviders($providers['providers']);
        $this->assertEquals($providers['providers'], $embed->getProviders());
    }


    public function testEmbedUrlParsing()
    {
        $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ');
        $this->assertInstanceOf('Cohensive\Embed\Embed', $this->embed->parseUrl());

        $this->embed->setUrl('http://hello.world/videoID');
        $this->assertFalse($this->embed->parseUrl());
    }


    public function testEmbedWithSSLAndSSLProvider()
    {
        $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ')->parseUrl();
        $this->assertEquals('https://youtu.be/dQw4w9WgXcQ', $this->embed->getProvider()->info->url);
    }

    public function testEmbedWithSSLAndNonSSLProvider()
    {
        $this->embed->setUrl('http://twitch.tv/day9tv')->parseUrl();
        $this->assertEquals('https://twitch.tv/day9tv', $this->embed->getProvider()->info->url);
    }


    public function testEmbedHTMLGeneration()
    {
        $this->embed->setUrl('http://youtu.be/dQw4w9WgXcQ')->parseUrl();

        $this->assertEquals('<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent" width="560" height="315" allowfullscreen="" frameborder="0"></iframe>', $this->embed->getHtml());
        $this->assertEquals('<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&wmode=transparent" width="560" height="315" allowfullscreen="" frameborder="0"></iframe>', $this->embed->getIframe());
    }

    public function testHTML5VideoGeneration()
    {
        $this->embed->setUrl('http://example.com/hello.mp4')->parseUrl();

        $this->assertEquals('<video width="560" height="315" controls="controls"><source type="video/webm" src="http://example.com/hello.webm"></source><source type="video/ogg" src="http://example.com/hello.ogg"></source><source type="video/mp4" src="http://example.com/hello.mp4"></source></video>', $this->embed->getHtml());
    }


}
