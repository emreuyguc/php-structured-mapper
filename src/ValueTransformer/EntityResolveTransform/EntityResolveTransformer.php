<?php

namespace Euu\StructuredMapper\ValueTransformer\EntityResolveTransform;

use Euu\StructuredMapper\ValueTransformer\Base\Exception\ValueTransformationException;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerInterface;
use Euu\StructuredMapper\ValueTransformer\Base\ValueTransformerMeta;
use Euu\StructuredMapper\ValueTransformer\EntityResolveTransform\Base\EntityResolveRepositoryAdapter;

class EntityResolveTransformer implements ValueTransformerInterface
{
    public function __construct(private readonly EntityResolveRepositoryAdapter $entityRepositoryAdapter)
    {
    }

    public function transform(EntityResolve|ValueTransformerMeta $transformerMeta, mixed $value, array &$mappingContext = []): mixed
    {
        if (!$transformerMeta instanceof EntityResolve) {
            throw new \InvalidArgumentException('Expected transformerMeta to be of type ResolveEntity.');
        }

        $repository = $this->entityRepositoryAdapter->getRepository($transformerMeta->entity);
        if ($repository === null) {
            throw new ValueTransformationException(sprintf('Entity Repository "%s" not found!', $transformerMeta->entity));
        }

        if (!method_exists($repository, $transformerMeta->repositoryMethod)) {
            throw new ValueTransformationException(sprintf(
                'The repository find method "%s" was not found in the Entity Repository "%s".',
                $transformerMeta->repositoryMethod,
                $transformerMeta->entity
            ));
        }

        //todo custom arguments has a symfony expression language
        $customArguments = null;
        foreach ($transformerMeta->findArguments ?? [] as $key => $arg) {
            $customArguments[$key] = str_replace('{{value}}', $value, $arg);
        }

        $found = $repository->{$transformerMeta->repositoryMethod}(...[$customArguments ?? $value]);

        if ($transformerMeta->nullable === false && $found === null) {
            throw new ValueTransformationException(
                sprintf(
                    'Value for entity "%s" could not be found in the repository. A valid entity is required.',
                    $transformerMeta->entity
                )
            );
        }

        return $found;
    }
}
