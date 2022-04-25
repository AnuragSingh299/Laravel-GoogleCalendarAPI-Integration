<?php

namespace App\Repositories;

use App\Interfaces\EventRepositoryInterface;
use App\Models\Event;

    class EventRepository implements EventRepositoryInterface
    {
        public function all()
        {
            $events = Event::all();
            return $events;
        }

        public function create($data)
        {
           return Event::create($data);
        }

        public function find($id) 
        {
            $event = Event::find($id);
            return $event;
        }

        public function update($id, $data)
        {
            $event = Event::find($id);
            $event->fill($data)->save();
            return $event;
        }

        public function delete($id)
        {
            return Event::find($id)->delete();
        }
        
    }
?>