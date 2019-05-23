<?php

namespace App\Api;

use App\Entity\Customer;
use App\Entity\Employee;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;

final class SerializationGroupsPrefixer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = self::class . '_called';

    private const PREFIXES = [
        Employee::class => 'employee',
        Customer::class => 'customer',
    ];

    /**
     * @var Security
     */
    private $security;

    /**
     * SerializationGroupsPrefixer constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param UserInterface|null $user
     * @return string|null
     */
    private function getPrefix(?UserInterface $user): ?string
    {
        if (null === $user) {
            return null;
        }

        return self::PREFIXES[\get_class($user)] ?? null;
    }

    /**
     * @param mixed $object
     * @param null  $format
     * @param array $context
     * @return array|bool|float|int|string
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, $format = null, array $context = [])
    {

        $context[self::ALREADY_CALLED] = true;

        $prefix = $this->getPrefix($this->security->getUser());

        if (null !== $prefix) {
            foreach ($context['groups'] as $group) {
                $context['groups'][] = sprintf('%s:%s', $prefix, $group);
            }
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * @param       $data
     * @param null  $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        // Make sure it's not called twice
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return true;
    }
}
