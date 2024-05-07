<?php

namespace Model\Api;

/**
 *
 */
interface VehicleRepositoryInterface
{
    /**
     * @param $vehicle
     * @return mixed
     */
    public function get($vehicle);

    /**
     * @param $vehicle
     * @return mixed
     */
    public function save($vehicle);

    /**
     * @param $vehicle
     * @return mixed
     */
    public function delete($vehicle);

    /**
     * @return mixed
     */
    public function list();
}