<?php

namespace TestProject;

/**
 * This class is a storage of catalog/file entries
 * This class implement __toString and __invoke magic
 *
 * Staring value of this class is full path to catalog/file
 *
 * When you call this class as method (__invoke magic) getMostUsedName will be called
 * @see self::getMostUsedName()
 * 
 * @author Dmytro S. <freelancerua@protonmail.com>
 */
class Entry
{
    /**
     * Path to file/catalog
     * @var string
     */
    protected $path;

    /**
     * Catelog's/file's name
     * @var string
     */
    protected $name;

    /**
     * File created date 
     * @see time()
     * @var int
     */
    protected $date;

    /**
     * Is file or catalog entry
     * @var bool
     */
    protected $is_file;

    /**
     * Child entries storage
     * @var Entry[]
     */
    protected $entries = [];

    /**
     * @param string $path Path to file/catalog entry
     * @param string $name Name of file/catalog entry
     * @param int $date Date of file/catalog has been created entry
     * @param bool $is_file Is file(true) or catelog(false) entry
     */
    public function __construct(string $path, string $name, int $date, bool $is_file = true)
    {
        $this->path = $path;
        $this->name = $name;
        $this->date = $date;
        $this->is_file = $is_file;
    }

    /**
     *  Build names array recursive
     */
    protected function walkRecursive() : array
    {
        $names = [];

        // Add self name to result calculation
        $names[] = $this->name;
        foreach ($this->entries as $entry)
        {
            if($entry->is_file) 
            {
                // Just add file name
                $names[] = $entry->name;
            } 
            else 
            {
                // Walk through sub catalog recursively
                $names = array_merge($names, $entry->walkRecursive());
            }
        }

        return $names;
    } 

    /**
     * Least one key will be in result
     * And here we ensure that pointer will be on first element
     * But if you need to rewind array use reset($names_counted)
     * ---------------------------------------------------------
     * In PHP 7.3 and higher:
     * return array_key_first($names_counted);
     * This function no matter of current array pointer
     * 
     * @return string Most using name of files and catalgos in catalog and child catalogs
     */
    public function getMostUsedName() : string
    {
        $names_counted = array_count_values($this->walkRecursive());
        arsort($names_counted);
        return key($names_counted);     
    }

    /**
     * Add new child entry
     * @param Entry $entry
     */
    public function addChildEntry(Entry $entry) : void
    {
        // Probably here exception would better then silent skip
        if(!$this->is_file) {
            $this->entries[] = $entry;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke() : string
    {
        return $this->getMostUsedName();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->path . DIRECTORY_SEPARATOR . $this->name;
    }
}
