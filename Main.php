<?php

    namespace IdnoPlugins\WordPress {

        class Main extends \Idno\Common\Plugin
        {

            function registerPages()
            {
                // Register settings page
                \Idno\Core\site()->addPageHandler('account/wordpress', '\IdnoPlugins\WordPress\Pages\Account');

                /** Template extensions */
                // Add menu item to account screen
                \Idno\Core\site()->template()->extendTemplate('account/menu/items', 'account/wordpress/menu');
            }

            function registerEventHooks()
            {
				\Idno\Core\site()->syndication()->registerService('wordpress', function() {
                    return $this->hasWordPress();
                }, ['article']);
                
                // Push "articles" to WordPress
                \Idno\Core\site()->addEventHook('post/article/wordpress', function (\Idno\Core\Event $event) {
                    if ($this->hasWordPress()) {
                        $object     = $event->data()['object'];
                        $title      = $object->getTitle();
                        $body		= $object->getDescription();
                        $data = array(
                            'data[title]' => $title,
                            'data[content_raw]' => $body,
                            'data[status]' => 'publish'
                        );
                        $result = $this->wpapi("posts",$data);  
                            if ($result['ID']) {
                                    $object->setPosseLink('wordpress',$result['link']);
                                    $object->save();
                            }
                    }
                });
                
            }
            
             /**
             * WordPress API Connector
             */
            function wpapi($command,$args)
            {
                
	            $username = \Idno\Core\site()->config()->wordpress['wp_username'];
	            $password = \Idno\Core\site()->config()->wordpress['wp_password'];
	            $url = \Idno\Core\site()->config()->wordpress['wp_url'];
	            $key = base64_encode($username . ':' . $password);
	            $siteurl = $url . '/wp-json/' . $command;
				$query_string = "";
				foreach ($args AS $k=>$v) $query_string .= "$k=".urlencode($v)."&";
				$curl = curl_init();
				$header = array();
				$header[] = 'Authorization: Basic ' . $key;
				curl_setopt($curl, CURLOPT_URL, $siteurl);
			    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			    curl_setopt($curl, CURLOPT_POSTFIELDS, $query_string);
			    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			    $result = curl_exec($curl);
			    $post = json_decode($result,true);
			    return $post;
            }
            
            /**
             * Can the current user use Twitter?
             * @return bool
             */
            function hasWordPress()
            {
                if (!empty(\Idno\Core\site()->config()->wordpress['wp_url'])) {
                    return true;
                }

                return false;
            }

        }

    }