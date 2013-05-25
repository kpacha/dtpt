<?php

namespace App\Service;

class MonitorService
{

    /**
     * The private collection
     * @var \MongoCollection
     */
    protected $_collection;

    public function __construct(\MongoCollection $collection)
    {
        $this->_collection = $collection;
    }

    public function insert($document, array $options = array())
    {
        return $this->_collection->insert($document, $options);
    }

    public function get(array $query = array(), array $fields = array())
    {
        return iterator_to_array($this->find($query, $fields));
    }

    public function find(array $query = array(), array $fields = array())
    {
        return $this->_collection->find($query, $fields);
    }

    public function aggregate(array $pipeline)
    {
        $aggregation = $this->_collection->aggregate($pipeline);
        return $aggregation['result'];
    }

    public function getAssistency($present = true)
    {
        $isPresentQuery = ($present) ? array('$ne' => 'No vota') : 'No vota';
        $pipeline = array(
            array('$project' => array('Totales.Presentes' => 1, 'Votaciones' => 1)),
            array('$match' => array('Totales.Presentes' => array('$ne' => 0))),
            array('$project' => array('Votaciones' => 1)),
            array('$unwind' => '$Votaciones.Votacion'),
            array('$match' => array('Votaciones.Votacion.Voto' => $isPresentQuery)),
            array('$group' => array(
                    '_id' => '$Votaciones.Votacion.Diputado',
                    'total' => array('$sum' => 1))
            ),
            array('$sort' => array('total' => -1))
        );
        return $this->aggregate($pipeline);
    }

    public function getSessions($detailed = false)
    {
        $pipeline = array(
            array('$project' => array('Informacion' => 1, 'Totales' => 1)),
            array('$match' => array('Totales.Presentes' => array('$ne' => 0))),
            array('$group' => array(
                    '_id' => '$Informacion.Sesion',
                    'total' => array('$sum' => 1),
                    'presents' => array('$avg' => '$Totales.Presentes'),
                    'notPresents' => array('$avg' => '$Totales.NoVotan'),
                    'maxPresents' => array('$max' => '$Totales.Presentes'),
                    'minPresents' => array('$min' => '$Totales.Presentes'))
            ),
            array('$sort' => array('_id' => 1))
        );
        if(!$detailed) {
            $pipeline[] = array('$group' => array(
                    '_id' => null,
                    'sessions' => array('$sum' => 1),
                    'total' => array('$sum' => '$total'),
                    'presents' => array('$avg' => '$presents'),
                    'notPresents' => array('$avg' => '$notPresents'),
                    'maxPresents' => array('$max' => '$maxPresents'),
                    'minPresents' => array('$min' => '$minPresents'),
                    'avgMaxPresents' => array('$avg' => '$maxPresents'),
                    'avgMinPresents' => array('$avg' => '$minPresents'))
            );
        }
        return $this->aggregate($pipeline);
    }

}
