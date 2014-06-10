<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\WordPress\Pages {

        /**
         * Default class to serve the homepage
         */
        class Account extends \Idno\Common\Page
        {

            function getContent()
            {
                $t = \Idno\Core\site()->template();
                $body = $t->draw('account/wordpress');
                $t->__(['title' => 'Wordpress', 'body' => $body])->drawPage();
            }

            function postContent() {
                $wp_user = $this->getInput('wp_username');
                $wp_pass = $this->getInput('wp_password');
                $wp_url = $this->getInput('wp_url');
                \Idno\Core\site()->config->config['wordpress'] = [
                    'wp_username' => $wp_user,
                    'wp_password' => $wp_pass,
                    'wp_url' => $wp_url
                ];
                \Idno\Core\site()->config()->save();
                \Idno\Core\site()->session()->addMessage('Your WordPress information was saved.');
                $this->forward('/account/wordpress/');
            }

        }

    }