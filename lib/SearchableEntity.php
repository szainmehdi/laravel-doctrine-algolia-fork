<?php

namespace Zain\LaravelDoctrine\Algolia;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Support\Arrayable;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Zain\LaravelDoctrine\Algolia\Exception\ConfigurationException;
use Zain\LaravelDoctrine\Algolia\Exception\ConfigurationException as Exception;

/**
 * @internal
 */
final class SearchableEntity
{
    private string $indexName;
    private object $entity;
    private ClassMetadata $entityMetadata;
    private bool $useSerializerGroups;

    /** @var mixed */
    private $normalizer;

    /** @var int|string */
    private $id;

    /**
     * @param array<string, int|string|array|bool> $extra
     * @param mixed $normalizer
     */
    public function __construct(
        string $indexName,
        object $entity,
        ClassMetadata $entityMetadata,
        $normalizer,
        array $extra = []
    ) {
        $this->indexName = $indexName;
        $this->entity = $entity;
        $this->entityMetadata = $entityMetadata;
        $this->normalizer = $normalizer;
        $this->useSerializerGroups = isset($extra['useSerializerGroup']) && $extra['useSerializerGroup'];

        $this->setId();
    }

    private function setId(): void
    {
        $ids = $this->entityMetadata->getIdentifierValues($this->entity);

        if (count($ids) === 0) {
            throw new Exception('Entity has no primary key');
        }

        if (count($ids) == 1) {
            $this->id = reset($ids);
        } else {
            $objectID = '';
            foreach ($ids as $key => $value) {
                $objectID .= $key . '-' . $value . '__';
            }

            $this->id = rtrim($objectID, '_');
        }
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    /**
     * @return array<string, int|string|array>
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function getSearchableArray(): array
    {
        $context = [
            'fieldsMapping' => $this->entityMetadata->fieldMappings,
        ];

        if ($this->useSerializerGroups) {
            $context['groups'] = [Searchable::NORMALIZATION_GROUP];
        }

        if ($this->normalizer instanceof NormalizerInterface) {
            return $this->normalizer->normalize($this->entity, Searchable::NORMALIZATION_FORMAT, $context);
        } else {
            throw new ConfigurationException(ClassUtils::getClass($this->entity) . ' cannot be serialized.');
        }
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
}
