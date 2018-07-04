namespace CG\Tests\Generator\Fixture;

use \DateTime;
use CG\Tests\Generator\Fixture\SubFixture\Foo;
use CG\Tests\Generator\Fixture\SubFixture as Sub;

/**
 * Doc Comment.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class EntityPhp7
{
    /**
     * @var integer
     */
    private $id = 0;

    public function setTimeZone(\DateTimeZone $timezone)
    {
    }

    public function setTime(\DateTime $time)
    {
    }

    /**
     * @param int $id
     * @return EntityPhp7
     */
    public function setId(int $id = NULL): self
    {
        $this->id = $id;
                return $this;
    }

    public function setArray(array &$array = NULL): array
    {
    }

    public function getTimeZone(): \DateTimeZone
    {
    }

    public function getTime(): \DateTime
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getFoo(): \CG\Tests\Generator\Fixture\SubFixture\Foo
    {
    }

    public function getBaz(): \CG\Tests\Generator\Fixture\SubFixture\Baz
    {
    }

    public function getBar(): \CG\Tests\Generator\Fixture\SubFixture\Bar
    {
    }
}