<?php

class Paginator {
    private $totalRecords;
    private $limit;
    private $currentPage;
    private $totalPages;
    private $url;

    public function __construct($totalRecords, $limit, $currentPage, $url = '?page=') {
        $this->totalRecords = $totalRecords;
        $this->limit = $limit;
        $this->currentPage = $currentPage;
        $this->totalPages = ceil($totalRecords / $limit);
        $this->url = $url;
    }

    public function createLinks() {
        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center flex-wrap">';

        // Botón de Anterior
        $html .= '<li class="page-item ' . ($this->currentPage <= 1 ? 'disabled' : '') . '">';
        $html .= '<a class="page-link" href="' . $this->url . max(1, $this->currentPage - 1) . '" aria-label="Previous">';
        $html .= '<span aria-hidden="true">&laquo;</span></a></li>';

        // Números de Página
        $start = max(1, $this->currentPage - 2);
        $end = min($this->totalPages, $this->currentPage + 2);

        if ($start > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->url . '1">1</a></li>';
            if ($start > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $html .= '<li class="page-item ' . ($i == $this->currentPage ? 'active' : '') . '">';
            $html .= '<a class="page-link" href="' . $this->url . $i . '">' . $i . '</a></li>';
        }

        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->url . $this->totalPages . '">' . $this->totalPages . '</a></li>';
        }

        // Botón de Siguiente
        $html .= '<li class="page-item ' . ($this->currentPage >= $this->totalPages ? 'disabled' : '') . '">';
        $html .= '<a class="page-link" href="' . $this->url . min($this->totalPages, $this->currentPage + 1) . '" aria-label="Next">';
        $html .= '<span aria-hidden="true">&raquo;</span></a></li>';

        $html .= '</ul></nav>';

        return $html;
    }
}
?>