---
title: PageResource
description: PageResource
extends: _layouts.documentation
section: content
---

# PageResource {#page-resource}

PageResource serve a creare una pagina a blocchi dalla sezione "Pagine" di Filament, in modo simile a Fabricator.

PageResource utilizza un field di tipo PageContent,

PageContent a sua volta va a prendersi i blocchi dalle cartelle Filament/Blocks di tutti i moduli,
e restituisce un array di blocchi tramite il Form Builder.

Quindi questo mette a disposizione tutti i blocchi all'interno del page content.

Poi c'è la parte del rendering dei blocchi, che parte dalla rotta "index" del tema, creata tramite Folio.

La rotta index richiama tramite il themeComposer il metodo showPageContent con lo slug, che nel caso di index è home.

ShowPageContent renderizza i content_blocks tramite il componente \Modules\UI\View\Components\Render\Blocks,
che a sua volta renderizza la lista dei blocchi tramite il ciclo che è dentro /Modules/UI/resources/views/components/render/blocks/v1.blade.php

La pagina Themes/Sixteen/resources/views/pages/pages/[slug].blade.php serve a renderizzare le altre pagine. Bisogna visitare l'url /it/pages/slug
