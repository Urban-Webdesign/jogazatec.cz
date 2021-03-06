<?php declare(strict_types = 1);

namespace App\Model;

use K2D\Core\Models\BaseModel;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

class NewModel extends BaseModel
{

	protected string $table = 'news';

	public function getNew(string $slug, string $lang): ?ActiveRow
	{
		return $this->getTable()->where('public', 1)->where('lang', $lang)->where('slug', $slug)->fetch();
	}

    public function getPublicNews(string $lang): Selection
    {
        return $this->getTable()->where('public', 1)->where('category_id', 1)->where('lang', $lang)->order('created DESC')->order('id DESC');
    }

    public function getFutureEvents(string $today): Selection
    {
        return $this->getTable()->where('public', 1)->where('created >=', $today)->where('category_id', 2)->order('created ASC')->limit(4);
    }

    public function getPastEvents(string $today): Selection
    {
        return $this->getTable()->where('public', 1)->where('created <', $today)->where('category_id', 2)->order('created DESC');
    }


}
