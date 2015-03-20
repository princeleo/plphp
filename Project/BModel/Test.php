<?php

class Project_BModel_Test
{
    public static function getItem()
    {

        return Project_Model_Test::dataAccess()
            ->filter('dealId', '1345601766')
            ->find();
    }
}

