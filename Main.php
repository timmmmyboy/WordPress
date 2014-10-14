<?php

    namespace IdnoPlugins\WordPress {

        class Main extends \Idno\Common\Plugin {

            function registerPages(){
                // Register settings page
                \Idno\Core\site()->addPageHandler('account/wordpress/?', '\IdnoPlugins\WordPress\Pages\Account');

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
						$wpClient = $this->connect();
						$object = $event->data()['object'];
                        $title = $object->getTitle();
                        $body = $object->getDescription();
                        $hashtags = $object->getTags();
                        foreach($hashtags as $tag){
                        $tags[] = str_replace('#', '', $tag);
                        }
                        $content['terms_names'] = array('post_tag' => $tags);
						
						$post = $wpClient->newPost($title, $body, $content);
                        if ($post) {
                        	$postinfo = $wpClient->getPost($post);
                            $object->setPosseLink('wordpress',$postinfo['link']);
                            $object->save();
                        }
                    }
                });
                
            }
            
             /**
             * Connect to WordPress
             * @return bool|\wpClient
             */
            function connect(){
            	require_once(dirname(__FILE__) . '/external/WordpressClient.php');
                		                
	            $username = \Idno\Core\site()->config()->wordpress['wp_username'];
	            $password = \Idno\Core\site()->config()->wordpress['wp_password'];
	            $url = \Idno\Core\site()->config()->wordpress['wp_url'];
				$url .= (substr($url, -1) == '/' ? '' : '/');
                $endpoint = $url . 'xmlrpc.php';
				
				# Create client instance
				$wpClient = new \HieuLe\WordpressXmlrpcClient\WordpressClient();
								
				# Set the credentials for the next requests
				$wpClient->setCredentials($endpoint, $username, $password);
				return $wpClient;
            }

            
            
            
            /**
             * Do we have WordPress information?
             * @return bool
             */
            function hasWordPress()
            {
                if (!empty(\Idno\Core\site()->config()->wordpress)) {
                    return true;
                }

                return false;
            }

        }

    }