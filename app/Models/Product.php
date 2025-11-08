<?php
namespace App\Models;

class Product
{
    private $id;
    private $title;
    private $slug;
    private $category;
    private $shortDescription;
    private $description;
    private $price;
    private $rating;
    private $image;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    // Getters e Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }

    public function getSlug() { return $this->slug; }
    public function setSlug($slug) { $this->slug = $slug; }

    public function getCategory() { return $this->category; }
    public function setCategory($category) { $this->category = $category; }

    public function getShortDescription() { return $this->shortDescription; }
    public function setShortDescription($shortDescription) { $this->shortDescription = $shortDescription; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getPrice() { return $this->price; }
    public function setPrice($price) { $this->price = $price; }

    public function getRating() { return $this->rating; }
    public function setRating($rating) { $this->rating = $rating; }

    public function getImage() { return $this->image; }
    public function setImage($image) { $this->image = $image; }
}
