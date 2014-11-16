<?php

namespace EntitySerializer;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class YamlEncoder implements EncoderInterface, DecoderInterface
{
    const FORMAT = 'yaml';

    public function __construct()
    {
    }

    public function encode($data, $format, array $context = array())
    {
        return \Symfony\Component\Yaml\Yaml::dump($data);
    }

    public function decode($data, $format, array $context = array())
    {
        return \Symfony\Component\Yaml\Yaml::parse($data);
    }

    public function supportsEncoding($format)
    {
        return self::FORMAT === $format;
    }

    public function supportsDecoding($format)
    {
        return self::FORMAT === $format;
    }
}

