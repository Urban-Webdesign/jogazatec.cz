<?php

namespace App\Service;

use K2D\Core\Models\ConfigurationModel;
use K2D\Core\Models\LogModel;
use K2D\Core\Service\ModelRepository;
use K2D\File\Model\FileModel;
use App\Model\CategoryModel;
use App\Model\NewModel;
use Nette\Database\Table\Selection;

/**
 * @property-read NewModel $new
 * @property-read FileModel $file
 * @property-read CategoryModel $category
 */
class ProjectModelRepository extends ModelRepository
{

    public function get3NewsByCategory(string $categoryName): Selection
    {
        return $this->category->getItemBy($categoryName, 'name')->related('news.category_id')->order('created DESC')->limit(3);
    }
}
