<?php

namespace ProxyProviderBridge\Service\Serializer;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Serialization handler
 */
class SerializerService
{
    /**
     * @return SerializerInterface
     */
    public static function getSerializer(): SerializerInterface
    {
        $objectNormalizer = new ObjectNormalizer(
            null,
            null,
            null,
            new ReflectionExtractor(),
            null,
            null,
            [
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true, // let php resolve the type via auto cas instead of serializer checking strict type match
            ]
        );

        $enumNormalizer = new BackedEnumNormalizer();
        $serializer     = new Serializer([$enumNormalizer, $objectNormalizer], [new JsonEncoder()]);

        return $serializer;
    }
}