<?php
namespace App\Controllers\BettingCalculator\BettingSystems;

/**
 * Provides the ability to perform auto betting system selection
 * if system exists.
 */
class BettingSystemsChooser {


    protected $system;
    public $slug;


    public function __construct($system, $slug){
        $this->system = $system;
        $this->slug = $slug;
    }

    /**
     * @throws \ReflectionException
     */
    public function index(): array{

        $class = '\App\Controllers\BettingCalculator\BettingSystems\System'.$this->system;

        if (! class_exists($class)){
          throw  \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $instance = new \ReflectionClass($class);
        $obj = $instance->newInstanceArgs();

        $data = $this->present($obj);

        if ($data['slug'] !== $this->slug){
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        }

        return $data;

    }


    private function present($obj): array{
        $data['system'] = $obj->system;
        $data['title'] = $obj->title;
        $data['slug'] = url_title($obj->title, '-', true);
        $data['description'] = $obj->description;
        $data['min_odds'] = $obj->min_odds;
        $data['max_odds'] = $obj->max_odds;
        $data['min_percentage'] = $obj->min_percentage;
        $data['is_published'] = $obj->is_published;
        $data['games'] = $obj->index();

//        saving system's number and title to db
        $db = db_connect();
        $builder = $db->table('systems');
        $exists = @$builder->where('number', $this->system)->get()->getResult()[0];
        $arr = ['number' => $this->system, 'title' => $obj->title];
        if (!$exists){
            $builder->insert($arr);
        }else{
             $builder->update($arr, ['id' => $exists->id]);
        }

        return $data;
    }


}