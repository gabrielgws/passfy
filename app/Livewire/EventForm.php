<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventForm extends Component
{
    use WithFileUploads;

    public $eventId;
    public $title = '';
    public $short_description = '';
    public $description = '';
    public $category_id = '';
    public $start_date = '';
    public $end_date = '';
    public $location = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zip_code = '';
    public $status = 'draft';
    public $is_featured = false;
    public $is_free = false;
    public $categories = [];
    public $image;
    public $currentImage = '';

    public function mount($eventId = null)
    {
        $this->categories = Category::active()->orderBy('name')->get();
        $this->eventId = $eventId;
        if ($eventId) {
            $event = Event::where('user_id', Auth::id())->findOrFail($eventId);
            $this->fill($event->only([
                'title',
                'short_description',
                'description',
                'category_id',
                'start_date',
                'end_date',
                'location',
                'address',
                'city',
                'state',
                'zip_code',
                'status',
                'is_featured',
                'is_free',
            ]));
            $this->start_date = $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '';
            $this->end_date = $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '';
            $this->currentImage = $event->image;
        }
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:120',
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:120',
            'address' => 'nullable|string|max:255',
            'city' => 'required|string|max:80',
            'state' => 'required|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'status' => 'required|in:draft,published,cancelled,finished',
            'is_featured' => 'boolean',
            'is_free' => 'boolean',
            'image' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif,webp',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => Auth::id(),
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'short_description' => $this->short_description,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'address' => $this->address,
            'city' => $this->city,
            'state' => strtoupper($this->state),
            'zip_code' => $this->zip_code,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
            'is_free' => $this->is_free,
        ];

        // Handle image upload
        if ($this->image) {
            // Delete old image if exists
            if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                Storage::disk('public')->delete($this->currentImage);
            }

            // Store new image
            $imagePath = $this->image->store('events', 'public');
            $data['image'] = $imagePath;
        }

        if ($this->eventId) {
            $event = Event::where('user_id', Auth::id())->findOrFail($this->eventId);
            $event->update($data);
        } else {
            Event::create($data);
        }

        $this->dispatch('eventSaved')->to('event-manager');
    }

    public function cancel()
    {
        // Clean up uploaded image if form is cancelled
        if ($this->image) {
            $this->image = null;
        }
        $this->dispatch('closeEventForm')->to('event-manager');
    }

    public function removeImage()
    {
        if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
            Storage::disk('public')->delete($this->currentImage);
        }
        $this->currentImage = '';
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.event-form');
    }
}
