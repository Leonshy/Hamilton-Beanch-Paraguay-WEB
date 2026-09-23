<?php

namespace App\Traits;

trait EscapesLikeSearch
{
    /**
     * Escapa los comodines de SQL LIKE (%, _) en un término de búsqueda
     * escrito por el usuario, para que "50%" busque el texto literal "50%"
     * en vez de comportarse como un comodín que matchea cualquier cosa.
     *
     * Se usa junto con la cláusula ESCAPE '\' en el where, ej.:
     *   $q->whereRaw("title LIKE ? ESCAPE '\\\\'", ['%' . $this->escapeLike($search) . '%'])
     */
    protected function escapeLike(string $term): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term);
    }
}
