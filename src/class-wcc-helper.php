<?php

namespace wcc;

class WCC_Helper {

    /**
     * Return wether or not the user is allowed to see the given content
     *
     * @param array $atts attributes from the shortcode.
     * @return boolean
     */
    public static function is_user_valid( $atts ) {
        $unlock = false;
        $user   = wp_get_current_user();
        if ( isset( $atts['role'] ) && ! empty( $atts['role'] ) ) {
            $roles = $user->roles;
            if ( strpos( $atts['role'], ',' ) !== false ) {
                $roles_array = explode( ',', $atts['role'] );
                foreach ( $roles_array as $role ) {
                    if ( $role === $roles[0] ) {
                        $unlock = true;
                    }
                }
            } else {
                if ( $atts['role'] === $roles[0] ) {
                    $unlock = true;
                }
            }
        }
        if ( isset( $atts['user'] ) && ! empty( $atts['user'] ) ) {
            if ( strpos( $atts['user'], ',' ) !== false ) {
                $users_array = explode( ',', $atts['user'] );
                foreach ( $users_array as $user ) {
                    if ( $user === $user->user_login ) {
                        $unlock = true;
                    }
                }
            } else {
                if ( $atts['user'] === $user->user_login ) {
                    $unlock = true;
                }
            }
        }
        return $unlock;
    }
}

