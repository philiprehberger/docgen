<?php

namespace Tests\Unit;

use App\Services\Twig\FieldDiscovery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FieldDiscoveryTest extends TestCase
{
    #[Test]
    public function it_returns_no_fields_for_a_template_with_no_variables(): void
    {
        $schema = (new FieldDiscovery)->discover('<p>Hello world</p>');

        $this->assertSame(['fields' => []], $schema);
    }

    #[Test]
    public function it_finds_a_scalar_variable(): void
    {
        $schema = (new FieldDiscovery)->discover('<p>Hello {{ name }}</p>');

        $this->assertSame([
            'fields' => [
                ['name' => 'name', 'type' => 'scalar', 'required' => true],
            ],
        ], $schema);
    }

    #[Test]
    public function it_finds_multiple_scalars_without_duplicates(): void
    {
        $schema = (new FieldDiscovery)->discover('Hi {{ first_name }} {{ last_name }}. Confirming {{ first_name }}.');

        $names = array_column($schema['fields'], 'name');

        sort($names);

        $this->assertSame(['first_name', 'last_name'], $names);
    }

    #[Test]
    public function it_infers_object_type_from_dotted_access(): void
    {
        $schema = (new FieldDiscovery)->discover('Hello {{ user.name }} of {{ user.company }}');

        $this->assertCount(1, $schema['fields']);
        $this->assertSame('user', $schema['fields'][0]['name']);
        $this->assertSame('object', $schema['fields'][0]['type']);

        $childNames = array_column($schema['fields'][0]['children'], 'name');
        sort($childNames);
        $this->assertSame(['company', 'name'], $childNames);
    }

    #[Test]
    public function it_infers_array_type_from_for_loop(): void
    {
        $schema = (new FieldDiscovery)->discover(<<<'TWIG'
            {% for item in items %}{{ item }}{% endfor %}
        TWIG);

        $this->assertCount(1, $schema['fields']);
        $this->assertSame('items', $schema['fields'][0]['name']);
        $this->assertSame('array', $schema['fields'][0]['type']);
        $this->assertSame('scalar', $schema['fields'][0]['item_type']);
    }

    #[Test]
    public function it_captures_object_shape_of_loop_items(): void
    {
        $schema = (new FieldDiscovery)->discover(<<<'TWIG'
            {% for line in lines %}
              <tr><td>{{ line.description }}</td><td>{{ line.amount }}</td></tr>
            {% endfor %}
        TWIG);

        $this->assertCount(1, $schema['fields']);
        $this->assertSame('lines', $schema['fields'][0]['name']);
        $this->assertSame('array', $schema['fields'][0]['type']);
        $this->assertSame('object', $schema['fields'][0]['item_type']);

        $childNames = array_column($schema['fields'][0]['children'], 'name');
        sort($childNames);
        $this->assertSame(['amount', 'description'], $childNames);
    }

    #[Test]
    public function it_excludes_loop_variable_from_root_fields(): void
    {
        $schema = (new FieldDiscovery)->discover(<<<'TWIG'
            {% for line in lines %}{{ line.amount }}{% endfor %}
        TWIG);

        $rootNames = array_column($schema['fields'], 'name');

        $this->assertContains('lines', $rootNames);
        $this->assertNotContains('line', $rootNames);
    }

    #[Test]
    public function it_excludes_set_variables_from_root_fields(): void
    {
        $schema = (new FieldDiscovery)->discover(<<<'TWIG'
            {% set total = subtotal + tax %}
            {{ total }} from {{ subtotal }} + {{ tax }}
        TWIG);

        $rootNames = array_column($schema['fields'], 'name');
        sort($rootNames);

        // `total` is local; `subtotal` and `tax` are template inputs.
        $this->assertSame(['subtotal', 'tax'], $rootNames);
    }

    #[Test]
    public function it_handles_filters_and_conditionals(): void
    {
        $schema = (new FieldDiscovery)->discover(<<<'TWIG'
            {% if client.verified %}{{ client.name|upper }}{% else %}Unverified{% endif %}
            Total: {{ amount|number_format(2) }}
        TWIG);

        $names = array_column($schema['fields'], 'name');
        sort($names);
        $this->assertSame(['amount', 'client'], $names);

        $client = collect($schema['fields'])->firstWhere('name', 'client');
        $this->assertSame('object', $client['type']);

        $childNames = array_column($client['children'], 'name');
        sort($childNames);
        $this->assertSame(['name', 'verified'], $childNames);
    }

    #[Test]
    public function it_handles_nested_loops_with_distinct_locals(): void
    {
        $schema = (new FieldDiscovery)->discover(<<<'TWIG'
            {% for invoice in invoices %}
              {{ invoice.number }}
              {% for line in invoice.lines %}{{ line.amount }}{% endfor %}
            {% endfor %}
        TWIG);

        $rootNames = array_column($schema['fields'], 'name');

        $this->assertSame(['invoices'], $rootNames);

        $invoices = $schema['fields'][0];
        $this->assertSame('array', $invoices['type']);
        $this->assertSame('object', $invoices['item_type']);

        $invoiceChildNames = array_column($invoices['children'], 'name');
        sort($invoiceChildNames);

        // Both `number` and `lines` are accessed on the loop item.
        $this->assertSame(['lines', 'number'], $invoiceChildNames);
    }

    #[Test]
    public function it_treats_pseudo_variables_as_internal(): void
    {
        $schema = (new FieldDiscovery)->discover(<<<'TWIG'
            {% for x in items %}{{ loop.index }}: {{ x }}{% endfor %}
        TWIG);

        $rootNames = array_column($schema['fields'], 'name');

        // `loop` is a Twig internal — never surfaced as a merge field.
        $this->assertNotContains('loop', $rootNames);
        $this->assertContains('items', $rootNames);
    }

    #[Test]
    public function it_throws_on_unparseable_template(): void
    {
        $this->expectException(\Throwable::class);

        (new FieldDiscovery)->discover('{% for %}');
    }
}
