<?php

function isAllowedViewModule($moduleName = '')
{
    $t = &get_instance();
    $moduleName = ($moduleName == '') ? $t->router->fetch_class() : $moduleName;

    $user = get_active_user();
    
    // Check if user is valid and has user_role_id property
    if (!$user || !is_object($user) || !isset($user->user_role_id)) {
        return false;
    }
    
    $user_roles = get_user_roles();

    if (isset($user_roles[$user->user_role_id])) {
        $permission = json_decode($user_roles[$user->user_role_id]);
        if (isset($permission->$moduleName) && isset($permission->$moduleName->read)) {
            return true;
        }
    }

    return false;
}

function isAllowedWriteModule($moduleName = '')
{
    $t = &get_instance();
    $moduleName = ($moduleName == '') ? $t->router->fetch_class() : $moduleName;

    $user = get_active_user();
    
    // Check if user is valid and has user_role_id property
    if (!$user || !is_object($user) || !isset($user->user_role_id)) {
        return false;
    }
    
    $user_roles = get_user_roles();

    if (isset($user_roles[$user->user_role_id])) {
        $permission = json_decode($user_roles[$user->user_role_id]);
        if (isset($permission->$moduleName) && isset($permission->$moduleName->write)) {
            return true;
        }
    }

    return false;
}

function isAllowedUpdateModule($moduleName = '')
{
    $t = &get_instance();
    $moduleName = ($moduleName == '') ? $t->router->fetch_class() : $moduleName;

    $user = get_active_user();
    
    // Check if user is valid and has user_role_id property
    if (!$user || !is_object($user) || !isset($user->user_role_id)) {
        return false;
    }
    
    $user_roles = get_user_roles();

    if (isset($user_roles[$user->user_role_id])) {
        $permission = json_decode($user_roles[$user->user_role_id]);
        if (isset($permission->$moduleName) && isset($permission->$moduleName->update)) {
            return true;
        }
    }

    return false;
}

function isAllowedDeleteModule($moduleName = '')
{
    $t = &get_instance();
    $moduleName = ($moduleName == '') ? $t->router->fetch_class() : $moduleName;

    $user = get_active_user();
    
    // Check if user is valid and has user_role_id property
    if (!$user || !is_object($user) || !isset($user->user_role_id)) {
        return false;
    }
    
    $user_roles = get_user_roles();

    if (isset($user_roles[$user->user_role_id])) {
        $permission = json_decode($user_roles[$user->user_role_id]);
        if (isset($permission->$moduleName) && isset($permission->$moduleName->delete)) {
            return true;
        }
    }

    return false;
}
