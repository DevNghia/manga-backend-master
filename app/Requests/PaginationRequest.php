<?php

namespace App\Requests;

class PaginationRequest
{
    private mixed $page;
    private mixed $itemsPerPage;

    public function __construct(array $pagination = null)
    {
        if (empty($pagination)) {
            $this->setPage(1);
            $this->setItemsPerPage(10);
        }
        else {
            if (empty($pagination['page']) || intval($pagination['page']) < 1) {
                $this->setPage(1);
            }
            else {
                $this->setPage($pagination['page']);
            }

            if (empty($pagination['per_page']) || intval($pagination['per_page']) < 1) {
                $this->setItemsPerPage(10);
            }
            else {
                $this->setItemsPerPage($pagination['per_page']);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getPage(): mixed
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage(mixed $page): void
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getItemsPerPage(): mixed
    {
        return $this->itemsPerPage;
    }

    /**
     * @param mixed $itemsPerPage
     */
    public function setItemsPerPage(mixed $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getOffset(): float|int
    {
        return ($this->page - 1) * $this->itemsPerPage;
    }
}
