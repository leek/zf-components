# Zend Framework Components by leek ![Project status](http://stillmaintained.com/leek/zf-components.png)
Collection of various helpers and extensions to the Zend Framework project.

## Leek_Config / Leek_Application_Resource_Config

Load as many config files as you'd like (of any type) in your Application config file.
Example (application.xml):

    ...
    <resources>
        ...
        <config>
            <navigation>
                <path>../application/configs/navigation.xml</path>
                <useEnvironment>true</useEnvironment>
                <cacheKey>navigation</cacheKey>
                <cacheManagerKey>config</cacheManagerKey>
            </navigation>
        </config>
    </resources>
    ...

#### Options
 * `path`: Path to config file
 * `useEnvironment`: Used if you want the config to have inheritence based on ENV
 * `cacheKey`: Key used to load from our Zend_Cache object (default: 'config')
 * `cacheManagerKey`: Key used to load from our CacheManager Resource (default: 'config')

Once you have this setup, you can do this anywhere to get your Zend_Config array:

    $config = Leek_Config::getBootstrapConfig('navigation')

## Leek_Captcha_ImageGrid

A Zend Framework implementation of [Image Recognition CAPTCHA](http://www.eecs.berkeley.edu/Pubs/TechRpts/2004/5256.html) (similar to [ASIRRA](http://research.microsoft.com/en-us/um/redmond/projects/asirra/), [PIX](http://www.captcha.net/captchas/pix/), etc.). See [this image](http://i.imgur.com/9IUFg.png) for a reference of what this CAPTCHA adapter could look like.

#### Example Controller Usage

    $captcha = new Leek_Captcha_ImageGrid(array('salt' => 'CHANGE_ME'));

    if ($this->_request->isPost()) {
        $formData = $this->_request->getPost();          
        if ($captcha->isValid($formData)) {
            // ...
        }
    } else {
        $captcha->generate();
    }

    $this->view->captcha = $captcha;

#### Example ViewScript Usage

    $this->captcha->render($this);

## Leek_Error

This is a fork of [FFFUU-Exception](https://github.com/kurtschwarz/FFFUU-Exception) error handler by [kurtschwarz](https://github.com/kurtschwarz). I loved the design but wanted something that could easily drop in to any of my Zend Framework projects (and wasn't NSFW) - so here it is. See [this image](http://i.imgur.com/lFjwF.jpg) for a reference of what this error handler looks like.

# Reference

* To create libonly/master:

    git subtree split --prefix=library/Leek -b libonly/master
