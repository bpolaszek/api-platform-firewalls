<?php

namespace App\Api;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ApiCollectionNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    /**
     * @var NormalizerInterface|NormalizerAwareInterface
     */
    private $decorated;

    /**
     * @var Security
     */
    private $security;

    public function __construct(
        NormalizerInterface $collectionNormalizer,
        Security $security
    ) {
        if (!$collectionNormalizer instanceof NormalizerAwareInterface) {
            throw new \InvalidArgumentException(
                sprintf('The decorated normalizer must implement the %s.', NormalizerAwareInterface::class)
            );
        }
        $this->decorated = $collectionNormalizer;
        $this->security = $security;
    }

    /**
     * @inheritdoc
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $user = $this->security->getUser();
        $data = ['@userClass' => null !== $user ? \get_class($user) : null];
        $data = \array_merge($data, $this->decorated->normalize($object, $format, $context));
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    /**
     * @inheritdoc
     */
    public function setNormalizer(NormalizerInterface $normalizer)
    {
        $this->decorated->setNormalizer($normalizer);
    }
}
