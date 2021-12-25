<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\Content;
use Livewire\Component;

class ShowBlog extends Component
{
    public $banner, $article;
    public function mount($id)
    {
        $this->banner = Content::where('id', $id)->where('content_type', 2)->first();
        $this->article =  Article::where('banner_id', $this->banner->id)->first();
        if (!$this->article) {
            $this->alert('error', 'Tidak ada artikel', [
                'position' =>  'center',
                'timer' =>  3000,
                'toast' =>  true,
                'text' =>  '',
                'confirmButtonText' =>  'Ok',
                'cancelButtonText' =>  'Cancel',
                'showCancelButton' =>  false,
                'showConfirmButton' =>  false,
            ]);
            return redirect()->to('/');
        }
    }
    public function render()
    {
        return view('livewire.show-blog')->layout('layouts.main');
    }
}
