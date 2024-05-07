<?php

namespace Model\Api;

interface VehicleRepositoryInterface
{
    public function get($vehicle);

    public function save($vehicle);

    public function delete($vehicle);

    public function list();
}