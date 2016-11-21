<?php

namespace CondoBundle\Constant;

class Modules
{
    const SERVICE_MODULE = [
        'ROLE_SERVICE_DASHBOARD' => 'role.service.dashboard',
        'ROLE_SERVICE_ORDER' => 'role.service.order',
        'ROLE_SERVICE_OPEN_TIME' => 'role.service.open.time',
        'ROLE_SERVICE_PRODUCT' => 'role.service.product',
        'ROLE_SERVICE_CONDOMINIUMS' => 'role.service.condominiums',
    ];

    const CONDO_MODULE = [
        'ROLE_CONDOMINIUM_DASHBOARD' => 'role.condominium.dashboard',
        'ROLE_CONDOMINIUM_CLIENT' => 'role.condominium.client',
        'ROLE_CONDOMINIUM_UNIT' => 'role.condominium.unit',
        'ROLE_CONDOMINIUM_SERVICES' => 'role.condominium.services',
        'ROLE_CONDOMINIUM_ISSUES' => 'role.condominium.issues',
        'ROLE_CONDOMINIUM_NEWS' => 'role.condominium.news',
        'ROLE_CONDOMINIUM_FEEDBACK' => 'role.condominium.feedback',
    ];
}
