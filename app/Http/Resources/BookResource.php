<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'isbn' => $this->isbn,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'author' => $this->author,
            'published' => date_format($this->published, "Y-m-d H:i:s"),
            'publisher' => $this->publisher,
            'pages' => $this->pages,
            'description' => $this->description,
            'website' => $this->website,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
