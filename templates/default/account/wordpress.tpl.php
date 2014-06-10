<div class="row">

    <div class="span10 offset1">
        <h1>WordPress</h1>
        <?=$this->draw('account/menu')?>
    </div>

</div>
<div class="row">
    <div class="span10 offset1">
        <form action="/account/wordpress/" class="form-horizontal" method="post">
            <div class="explanation">Connect to WordPress by entering authentication information below.</div>
            <div class="control-group">
                <label class="control-label" for="name">Site URL</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="http://example.com" class="span4" name="wp_url" value="<?=htmlspecialchars(\Idno\Core\site()->config()->wordpress['wp_url'])?>" >
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Username</label>
                <div class="controls">
                    <input type="text" id="name" placeholder="username" class="span4" name="wp_username" value="<?=htmlspecialchars(\Idno\Core\site()->config()->wordpress['wp_username'])?>" >
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="name">Password</label>
                <div class="controls">
                    <input type="password" id="name" placeholder="password" class="span4" name="wp_password" value="<?=htmlspecialchars(\Idno\Core\site()->config()->wordpress['wp_password'])?>" >
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
            
            <?= \Idno\Core\site()->actions()->signForm('/account/wordpress/')?>
        </form>
    </div>
</div>
