<?php

/*
 * UF AltPermissions Sprinkle
 *
 * @author    Louis Charette
 * @copyright Copyright (c) 2018 Louis Charette
 * @link      https://github.com/lcharette/UF_AltPermissions
 * @license   https://github.com/lcharette/UF_AltPermissions/blob/master/LICENSE.md (MIT License)
 */

/**
 * Routes for administrative role management.
 * Route anything related to the RoleController.
 */
$app->group('/roles/{seeker}', function () {
    $this->get('', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:pageList')
        ->setName('alt_uri_roles');

    $this->get('/r/{id}', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:pageInfo')
         ->setName('alt_uri_roles.view');
})->add('checkAuthSeeker')->add('authGuard');

$app->group('/api/roles/{seeker}', function () {
    $this->delete('/r/{id}', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:delete')
         ->setName('api.roles.delete');

    $this->get('', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:getList')
         ->setName('api.roles.sprunje');

    $this->get('/r/{slug}/permissions', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:getPermissions')
         ->setName('api.roles.get.permissions');

    $this->put('/r/{id}/permissions', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:updatePermissions')
         ->setName('api.roles.put.permissions');

    $this->post('', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:create')
         ->setName('api.roles.create.post');

    $this->put('/r/{id}', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:updateInfo')
         ->setName('api.roles.edit.put');
})->add('checkAuthSeeker')->add('authGuard');

$app->group('/modals/roles/{seeker}', function () {
    $this->get('/create', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:getModalCreate')
         ->setName('modal.roles.create');

    $this->get('/edit', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:getModalEdit')
         ->setName('modal.roles.edit');

    $this->get('/permissions', 'UserFrosting\Sprinkle\AltPermissions\Controller\RoleController:getModalEditPermissions')
         ->setName('modal.roles.permissions');
})->add('checkAuthSeeker')->add('authGuard');
