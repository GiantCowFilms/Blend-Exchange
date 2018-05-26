<?php

namespace BlendExchange\Model\Traits;

trait RandomId {

    protected function idSize () {
        return 10;
    }
    protected $alphabet = 'abdegjklmnopqrsvwxyz' . 'ABDEGJKLMNOPQRSVWXYZ' . '0123456789'; //Some letters removed to reduce the likelyhood of creating rude words.
    private function getRandomId () {
		do {
            $id = '';
            for($i = 0; $i < $this->idSize(); $i++) {
                $id = $id . $this->alphabet[random_int(0,strlen($this->alphabet) - 1)];
            }
		} while( parent::where($this->getKeyName(), $id)->exists() );
		return $id;
    }

    /**
     * Perform a model insert operation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return bool
     */
	protected function performInsert(\Illuminate\Database\Eloquent\Builder $query){
		$this->getConnection()->transaction(function() use ($query) {
			$this->setAttribute($this->getKeyName(), $this->getRandomId());
			return parent::performInsert($query);
		});
	}
}