<?php
namespace NaiveUserState;

class SessionService extends CollectionService
{

    public function apply(array &$data)
    {
        $data = $this->data;
    }
}
