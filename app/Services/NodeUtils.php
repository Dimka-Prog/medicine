<?php

declare(strict_types=1);

namespace App\Services;

class NodeUtils extends KnowledgeBase
{
    /**
     * Название поля для итераций
     *
     * @var string
     */
    private string $key = 'successors';

    /**
     * Возвращает первый найденный массив(узел) с указанным названием поля, если такой был найден.
     * Иначае возвращает исходный массив(узел).
     * Первым параметром принимает массив(узел) с которого нужно начать поиск,
     * а вторым параметром название поля узла, который нужно найти.
     *
     * @param array $node
     * @param string $value
     * @return array
     */
    protected function getFirstNode(array $node, string $value): array
    {
        foreach ($node as $currentNode) {
            if (!is_array($currentNode))
                return $this->getFirstNode($node[$this->key], $value);

            if (in_array($value, $currentNode)) {
                return $currentNode;
            } elseif (array_key_exists($this->key, $currentNode)) {
                $array = $this->getFirstNode($currentNode[$this->key], $value);
                if (in_array($value, $array))
                    return $array;
            } else
                return $node;
        }
        return $node;
    }

    /**
     * Возвращает массив узлов с указанным названием поля, если такие были найдены.
     * Иначае возвращает исходный массив(узел).
     * Первым параметром принимает массив(узел) с которого нужно начать поиск,
     * а вторым параметром название поля узла, который нужно найти.
     * Третьим параметром можно передать массив, в который будут добавлены новые найденные узлы
     *
     * @param array $node
     * @param string $value
     * @param array $arrayNodes
     * @return array
     */
    protected function getArrayNodes(array $node, string $value, array &$arrayNodes = []): array
    {
        foreach ($node as $currentNode) {
            if (!is_array($currentNode)) {
                return $this->getArrayNodes($node[$this->key], $value, $arrayNodes);
            }

            if (in_array($value, $currentNode)) {
                if (!empty($arrayNodes)) {
                    foreach ($arrayNodes as $node) {
                        if (!in_array($currentNode['id'], $node)) {
                            $arrayNodes[] = $currentNode;
                            break;
                        }
                    }
                } else $arrayNodes[] = $currentNode;
            } elseif (array_key_exists($this->key, $currentNode)) {
                $this->getArrayNodes($currentNode[$this->key], $value, $arrayNodes);
            } else return $arrayNodes;
        }
        return $arrayNodes;
    }
}
