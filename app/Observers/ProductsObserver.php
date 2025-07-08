<?php

namespace App\Observers;

use App\Models\Products;

class ProductsObserver
{
    /**
     * Handle the Products "created" event.
     */
    public function created(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "updated" event.
     */
    public function updated(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "deleted" event.
     */
    public function deleted(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "restored" event.
     */
    public function restored(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "force deleted" event.
     */
    public function forceDeleted(Products $products): void
    {
        //
    }
}
