<?php
namespace DomainLayer;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TreeGetSetNormalizer implements NormalizerInterface
{
    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param object $object object to normalize
     * @param string $format format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = array())
    {

        $result = [];
        $attributeList = $this->extractAttributes($object);

        foreach ($attributeList as $attribute) {
            $normalizeAttribute = $this->checkAttribute($object, $attribute, $context, $format);
            if (is_array($normalizeAttribute)) {
                $result = array_merge($result, $normalizeAttribute);
            }
        }
        return $result;
    }

    private function checkAttribute($object, $fieldName, $context, $format)
    {
        if (isset($context[$fieldName]) === false) {
            return $this->extractAttribute($object, $fieldName, $format, []);
        } elseif ($context[$fieldName] === false) {
            return false;
        } elseif (is_array($context[$fieldName])) {
            return $this->extractAttribute($object, $fieldName, $format, $context[$fieldName]);
        } elseif ($context[$fieldName] instanceof \Closure) {
            return [$fieldName => $context[$fieldName]($object)];
        } else {
            return $this->executeChildObjectMethod($object, $fieldName, $context);
        }
    }

    private function extractAttribute($object, $fieldName, $format, $context)
    {
        try {
            $value = $this->getAttributeValue($object, $fieldName);
        } catch (\Exception $exception) {
            return false;
        }

        if (empty($value)) {
            return [$fieldName => $value];
        }

        $normalizedValue = $value;
        if (is_object($value)) {
            if ($value instanceof Collection) {
                $normalizedChild = [];
                foreach ($value as $child) {
                    $normalizedChild[] = $this->normalize($child, $format, $context);
                }
                return [$fieldName => $normalizedChild];
            }

            return [$fieldName => $this->normalize($value, $format, $context)];
        }
        return [$fieldName => $normalizedValue];
    }

    private function executeChildObjectMethod($object, $fieldName, $context)
    {
        try {
            $childObject = $this->getAttributeValue($object, $fieldName);
            $method = $context[$fieldName];
            if (is_callable(array($childObject, $method))) {
                return [$fieldName => $childObject->$method()];
            } else {
                return false;
            }
        } catch (\Exception $exception) {
            return false;
        }
    }

    private function getAttributeValue($object, $attribute)
    {
        $ucfirsted = ucfirst($attribute);

        $getter = 'get'.$ucfirsted;
        if (is_callable(array($object, $getter))) {
            return $object->$getter();
        }

        $isser = 'is'.$ucfirsted;
        if (is_callable(array($object, $isser))) {
            return $object->$isser();
        }

        throw new \Exception('Not found getter of isser '.$attribute.' attribute.');
    }

    /**
     * Checks if a method's name is get.* or is.*, and can be called without parameters.
     *
     * @param \ReflectionMethod $method the method to check
     *
     * @return bool whether the method is a getter or boolean getter
     */
    private function isGetMethod(\ReflectionMethod $method)
    {
        $methodLength = strlen($method->name);

        return
            !$method->isStatic() &&
            (
                ((0 === strpos($method->name, 'get') && 3 < $methodLength) ||
                    (0 === strpos($method->name, 'is') && 2 < $methodLength)) &&
                0 === $method->getNumberOfRequiredParameters()
            )
            ;
    }

    protected function extractAttributes($object)
    {
        $reflectionObject = new \ReflectionObject($object);
        $reflectionMethods = $reflectionObject->getMethods(\ReflectionMethod::IS_PUBLIC);

        $attributes = array();
        foreach ($reflectionMethods as $method) {
            if (!$this->isGetMethod($method)) {
                continue;
            }

            $attributeName = lcfirst(substr($method->name, 0 === strpos($method->name, 'is') ? 2 : 3));

//            if ($this->isAllowedAttribute($object, $attributeName)) {
            $attributes[] = $attributeName;
//            }
        }

        return $attributes;
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed $data Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return  (is_object($data) && !$data instanceof \Traversable) && $this->supports(get_class($data));
    }

    private function supports($class)
    {
        $class = new \ReflectionClass($class);
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if ($this->isGetMethod($method)) {
                return true;
            }
        }

        return false;
    }
}
