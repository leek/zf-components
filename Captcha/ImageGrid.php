<?php

class Leek_Captcha_ImageGrid extends Zend_Captcha_Image
{      
    /**
     * @var string
     */
    protected $_partialScript = 'captcha.phtml';
    
    /**
     * @var string
     */
    protected $_partialModule = 'default';
    
    /**
     * @var string
     */
    protected $_salt;
    
    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::MISSING_VALUE => 'Empty captcha value',
        self::MISSING_ID    => 'Captcha ID field is missing',
        self::BAD_CAPTCHA   => 'Captcha value is wrong',
    );
    
    /**
     * @var string
     */
    protected $_imgDir = './images/captcha/';
    
    /**
     * @var string
     */
    protected $_imgUrl = '/images/captcha/?r=';

    /**
     * @var string
     */
    protected $_suffix = '.jpg';

    /**
     * @var int
     */
    protected $_width = 200;

    /**
     * @var int
     */
    protected $_height = 50;
   
    /**
     * @var array
     */
    protected $_grid = array();
    
    /**
     * @var int
     */
    protected $_gridX = 2;
    
    /**
     * @var int
     */
    protected $_gridY = 4;
    
    /**
     * @var string
     */
    protected $_validCategory;
    
    /**
     * @var array
     */
    protected $_valid;
    
    /**
     * @var array
     */
    protected $_invalidCategories = array();
    
    /**
     * @var int
     */
    protected $_targetNumber;
    
    /**
     * Constructor
     *
     * @param  array|Zend_Config $options
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        // Get category list
        $this->_categories = glob("{$this->getImgDir()}*", GLOB_ONLYDIR);
    }
    
    /**
     * Generate a new captcha
     *
     * @return string new captcha ID
     */
    public function generate()
    {
        // Generate session
        $id = Zend_Captcha_Word::generate();
        
        // Select a random valid category and set others to invalid
        $this->_validCategory     = $this->_randomCategory();
        $this->_invalidCategories = $this->getCategories();
        unset($this->_invalidCategories[$this->_validCategory]);
        
        // Select a random target number
        $gridTotal = $this->getGridX() * $this->getGridY();
        $this->_targetNumber = $this->_randomTargetNumber();
        
        // Fill grid
        for ($i = 0; $i < $gridTotal; $i++) {
            if ($i < $this->_targetNumber) {
                // Add valid image
                $this->_grid[] = array(
                    'categoryId' => $this->_validCategory,
                    'imagePath'  => $this->_randomImage($this->_validCategory),
                );
            } else {
                // Add invalid image
                $categoryId = $this->_randomCategory($this->_invalidCategories);
                $this->_grid[] = array(
                    'categoryId' => $categoryId,
                    'imagePath'  => $this->_randomImage($categoryId),
                );
            }
        }
        
        shuffle($this->_grid);
        
        // Store valids in session
        $valid = array();
        foreach ($this->_grid as $i => $gridItem) {
            if ($gridItem['categoryId'] == $this->_validCategory) {
                $valid[] = $i;
            }
        }
        $this->_setValidSelections($valid);
        
        return $id;
    }
    
    /**
     * Display the captcha
     *
     * @param  Zend_View_Interface $view
     * @param  mixed $element
     * @return string
     */
    public function render(Zend_View_Interface $view = null, $element = null)
    {
        echo $view->partial($this->getPartialScript(), $this->getPartialModule(), array(
            'grid'      => $this->_grid,
            'imageGrid' => $this,
        ));
    }
    
    /**
     * @see    Zend_Validate_Interface::isValid()
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        if (!is_array($value) && !is_array($context)) {
            $this->_error(self::MISSING_VALUE);
            return false;
        }
        
        if (!is_array($value) && is_array($context)) {
            $value = $context;
        }

        $name = $this->getName();

        if (isset($value[$name])) {
            $value = $value[$name];
        }

        if (!isset($value['input'])) {
            $this->_error(self::MISSING_VALUE);
            return false;
        }

        $input = $value['input'];
        $this->_setValue($input);

        if (!isset($value['id'])) {
            $this->_error(self::MISSING_ID);
            return false;
        }

        $this->_id = $value['id'];
        $valid = $this->getValidSelections();
        if (empty($valid)) {
            $this->_error(self::MISSING_ID);
            return false;
        }
        
        foreach ($valid as $validId) {
            if (!in_array($validId, $input)) {
                $this->_error(self::BAD_CAPTCHA);
                return false;
            }
        }

        return true;
    }
    
    /**
     * @return int
     */
    protected function _randomTargetNumber($gridTotal = null)
    {
        if ($gridTotal === null) {
            return mt_rand(2, floor(($this->getGridX() * $this->getGridY()) / 2));
        } else {
            return mt_rand(2, floor($gridTotal / 2));
        }
    }
    
    /**
     * @param array $categories
     * @return int
     */
    protected function _randomCategory($categories = null)
    {
        if ($categories === null) {
            return array_rand($this->getCategories(), 1);
        } else {
            return array_rand($categories, 1);
        }
    }
    
    /**
     * @param int $categoryId
     * @return string
     */
    protected function _randomImage($categoryId)
    {
        $images = glob("{$this->getCategory($categoryId)}/*{$this->getSuffix()}");
        return $images[array_rand($images, 1)];
    }
       
    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->_categories;
    }
    
    /**
     * @param int $key
     * @return array
     */
    public function getCategory($key)
    {
        return $this->_categories[$key];
    }
    
    /**
     * @param int|string $key
     * @return string
     */
    public function getCategoryName($key)
    {
        $category = $key;
        if (is_int($key)) {
            $category = $this->getCategory($key);
        }
        
        return substr($category, strrpos($category, '/') + 1);
    }
    
    public function getImgUrlFromGridItem($gridItem)
    {
        $random   = mt_rand(1000, 9999);
        $item     = $this->_grid[$gridItem];
        $category = $this->getCategoryName($item['categoryId']);
        $filename = substr($item['imagePath'], strrpos($item['imagePath'], '/') + 1);
        $filename = str_replace($this->getSuffix(), '', $filename);
        
        $params = array(
            'c'  => md5("{$this->getSalt()}:$random:$category"),
            'f'  => $filename,
            'id' => $random,
        );

        return $this->getImgUrl() . urlencode(base64_encode(http_build_query($params)));
    }
    
    /**
     * @return int
     */
    public function getValidCategory()
    {
        return $this->_validCategory;
    }
    
    /**
     * Get captcha selections that are valid
     *
     * @return array
     */
    public function getValidSelections()
    {
        if (empty($this->_valid)) {
            $session      = $this->getSession();
            $this->_valid = $session->valid;
        }
        return $this->_valid;
    }

    /**
     * Set captcha selections that are valid
     *
     * @param  array $valid
     * @return Zend_Captcha_Word
     */
    protected function _setValidSelections(array $valid)
    {
        $session        = $this->getSession();
        $session->valid = $valid;
        $this->_valid   = $valid;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getValidCategoryName()
    {
        return $this->getCategoryName($this->getValidCategory());
    }
    
    /**
     * @return int
     */
    public function getTargetNumber()
    {
        return $this->_targetNumber;
    }
    
    /**
     * @return string
     */
    public function getPartialScript()
    {
        return $this->_partialScript;
    }
    
    /**
     * @param string $moduleName
     * @return Leek_Captcha_ImageGrid 
     */
    public function setPartialModule($moduleName)
    {
        $this->_partialModule = $moduleName;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPartialModule()
    {
        return $this->_partialModule;
    }
    
    /**
     * @param string $filename
     * @return Leek_Captcha_ImageGrid 
     */
    public function setPartialScript($filename)
    {
        $this->_partialScript = $filename;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->_salt;
    }
    
    /**
     * @param string $value
     * @return Leek_Captcha_ImageGrid 
     */
    public function setSalt($value)
    {
        $this->_salt = $value;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getGridX()
    {
        return $this->_gridX;
    }
    
    /**
     * @param int $value
     * @return Leek_Captcha_ImageGrid 
     */
    public function setGridX($value)
    {
        $this->_gridX = (int) $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getGridY()
    {
        return $this->_gridY;
    }
    
    /**
     * @param int $value
     * @return Leek_Captcha_ImageGrid 
     */
    public function setGridY($value)
    {
        $this->_gridY = (int) $value;
        return $this;
    }
}