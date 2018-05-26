<?php 
trait MigrationRandomIdTrait {
    // Imitation of Models\Traits\RandomId::trait
    public function idSize () {
        return 8;
    }

    public $createdIds = [];
    public function getRandomId () {
        $alphabet = 'abdegjklmnopqrsvwxyz' . 'ABDEGJKLMNOPQRSVWXYZ' . '0123456789';
        do {
            $id = '';
            for($i = 0; $i < $this->idSize(); $i++) {
                $id = $id . $alphabet[random_int(0,strlen($alphabet) - 1)];
            }
        } while(in_array($id,$this->createdIds));
        //Removed. Simply unsustainable at scale. Gotta cross fingers and hope for no collision (this is more likely than may seem, due to the birthday paradox).
        //$this->createdIds[] = $id;
        return $id;
    }
}