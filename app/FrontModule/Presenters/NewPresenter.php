<?php declare(strict_types = 1);

namespace App\FrontModule\Presenters;

use K2D\Gallery\Models\ImageModel;
use App\Model\CategoryModel;
use App\Model\NewModel;

class NewPresenter extends BasePresenter
{

    /** @inject */
    public CategoryModel $categoryModel;

    /** @inject */
    public NewModel $newModel;

    /** @inject */
    public ImageModel $imageModel;

    public function renderDefault(string $category_name): void
    {
        $today = date('Y-m-d');
        $this->template->category_name = $category_name;

        if ($category_name === 'akce') {
            $this->template->articles = $this->newModel->getPastEvents($today);
        } else {
            $this->template->articles = $this->newModel->getPublicNews('cs');
        }
    }

    public function renderShow(string $category_name, string $slug): void
    {
        $this->template->category_name = $category_name;
        $new = $this->newModel->getNew($slug, 'cs');
        $this->template->new = $new;

        if ($new->gallery_id) {
            $this->template->images = $this->imageModel->getImagesByGallery($new->gallery_id);
        }
    }
}
