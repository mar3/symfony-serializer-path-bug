<?php

namespace App\Controller;

use App\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TestController extends AbstractController
{
    #[Route("/test", name: "test")]
    public function number(): Response
    {
        $normalizers = [
            new ArrayDenormalizer(),
            new ObjectNormalizer(null, null, null,
                new PropertyInfoExtractor(
                    [new ReflectionExtractor()],
                    [new PhpDocExtractor(), new ReflectionExtractor()],
                )
            ),
        ];
        $serializer = new Serializer($normalizers, [new JsonEncoder()]);
        try {
            $car = $serializer->deserialize('{
              "id": 1,
              "parts": [{
                "id": "int type"
              }],
              "owner": {
                "id": "int type"
              }
            }', Car::class, 'json', [DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true]);
        } catch (PartialDenormalizationException $e) {
            dd($e->getErrors());
        }

        return new Response();
    }
}