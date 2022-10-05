<?php
namespace Codad5\Wemall\View\V1;

Class Shop{
    private array $shop_type_array = ["clothing", "food", "automobile", "phones", "furnitures"];
    protected string $type;
    public function __construct(string $type)
    {
        $this->type = $type; 
    }

    public function load_html_form()
    {
        if(!in_array($this->type, $this->shop_type_array)){
            throw new CustomException('Invalid Shop Type', 303);
        }
        $form = file_get_contents(__DIR__ . "/html/{$this->type}.php");
        return $form;
    }
    
    
}