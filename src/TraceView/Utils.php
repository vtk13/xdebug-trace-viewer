<?php
namespace Vtk13\TraceView;

class Utils
{
    public static function mapOne(array $entityData, $className)
    {
        $entity = new $className();
        foreach ($entityData as $k => $v) {
            $entity->$k = $v;
        }
        return $entity;
    }

    public static function map(array $entitiesData, $className)
    {
        return array_map(function(array $entityData) use ($className) {
            $entity = new $className();
            foreach ($entityData as $k => $v) {
                $entity->$k = $v;
            }
            return $entity;
        }, $entitiesData);
    }
}
