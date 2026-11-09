<?php

declare(strict_types=1);

use Francken\Shared\Markdown\ContentCompiler;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateStudyResearchGroupsTable extends Migration
{
    /**
     * The research groups that used to be written in the ResearchGroupsController
     */
    private const GROUPS = [
        [
            'title' => 'Bio-inspired Circuits & Systems',
            'description' => 'We aim to identify the principles of neural computation and implement them in fully parallel and low-power neuromorphic very-large-scale integration (VLSI) systems that offer the opportunity to overcome the limitations of traditional digital architectures. Hereby we develop silicon implementations of neural networks with learning abilities and biologically inspired sensor systems, which allow us to validate current theories of learning and computation.',
            'photo' => 'https://www.rug.nl/research/zernike/research/topic_tcm.png',
            'units' => [
                ['Chicca', 'Bio-inspired Circuits & Systems', 'https://www.rug.nl/research/zernike/bio-inspired-circuits-and-systems/chicca-group/', 'https://www.rug.nl/research/zernike/bio-inspired-circuits-and-systems/chicca-group/contact', 'https://www.rug.nl/research/zernike/people/chicca-web-vk-150px.jpg'],
            ],
        ],
        [
            'title' => 'Device Physics of Complex Materials',
            'description' => 'Device physics contributes to our present knowledge of emergent behaviour of complex materials and probes its properties by making use of modern experimental tools and techniques based on nanotechnology. Emergent behaviour is prevalent in many complex materials and originates from competing interactions of electronic, magnetic and structural origin.',
            'photo' => 'https://www.rug.nl/research/zernike/device-physics-of-complex-materials/ye-group/YeWeb_460.png',
            'units' => [
                ['Ye', 'Device Physics of Complex Materials', 'https://www.rug.nl/research/zernike/device-physics-of-complex-materials/ye-group/', 'https://www.rug.nl/staff/j.ye/', 'https://www.rug.nl/research/zernike/people/Ye-1-WEB.jpg'],
            ],
        ],
        [
            'title' => 'Materials Science',
            'description' => "The principal aim of the research programme of the Applied Physics-Materials Science group is to search for the relation between the microstructure of materials and their physical properties. The programme concentrates on experimental and theoretical work of the characterization of line defects (dislocations and disclinations) and homo-/heterophase interfaces so as to draw conclusions about the correlation between atomic structure, electronic charge transfer, and physical properties, both structural and functional.\n\nThe research group for Materials Science is no longer an active research group, but it has been a vital part of the association over the years.",
            'photo' => 'https://www.rug.nl/research/zernike/materials-science/2_MK_ZIAMWEB.jpg',
            'units' => [
                ['De Hosson', 'Materials Science', 'https://www.rug.nl/research/zernike/materials-science/de-hosson-group/', 'https://www.rug.nl/staff/j.t.m.de.hosson/', 'https://www.rug.nl/research/zernike/people/DeHosson-1-WEB.jpg'],
            ],
        ],
        [
            'title' => 'Micromechanics',
            'description' => 'The mission of this research group is to develop new models and computational tools for the micromechanics of materials, and to employ these to develop relationships between the internal structure of a material and its mechanical properties. We study material properties at a range of length scales, placing special emphasis on scale transitions. We cover a variety of engineering materials, considering and exploiting the similarities and differences in their behaviour.',
            'photo' => 'https://www.rug.nl/research/zernike/micromechanics/MiMec_small.jpg',
            'units' => [
                ['Van der Giessen', 'Micromechanics of Materials', 'https://www.rug.nl/research/zernike/micromechanics/van-der-giessen-group/', 'https://www.rug.nl/staff/e.van.der.giessen/', 'https://www.rug.nl/research/zernike/people/VanderGiessen-1-WEB.jpg'],
                ['Onck', 'Micromechanics of Cellular and Active Materials', 'https://www.rug.nl/research/zernike/micromechanics/onck-group/', 'https://www.rug.nl/staff/p.r.onck/', 'https://www.rug.nl/research/zernike/people/Onck-1.jpg'],
                ['Giuntoli', 'Giuntoli group', 'https://www.rug.nl/research/zernike/micromechanics/giuntoli-group/', 'https://www.rug.nl/research/zernike/micromechanics/giuntoli-group/contact', 'https://www.rug.nl/research/zernike/micromechanics/giuntoli-group/giuntoli-150x150.jpg'],
            ],
        ],
        [
            'title' => 'Nanostructured Materials and Interfaces',
            'description' => 'Investigating the relation between nanostructure and functional properties of materials. Our research focus is on material structures, surfaces/interfaces, and surface interactions at the nanoscale. Our research interests include phase change materials, nanoclusters/nanoparticles, nanoresonators, surface forces, friction, and adhesion. Experimental facilities include scanning probe, scanning electron, and transmission electron microscopy. Applications of our research are connected with hydrogen storage, novel NEMS devices and phase change memories.',
            'photo' => 'https://www.rug.nl/research/zernike/nanostructured-materials-and-interfaces/NMI_group.jpg',
            'units' => [
                ['Kooi', 'Nanostructured Materials and Interfaces', 'https://www.rug.nl/research/zernike/nanostructured-materials-and-interfaces/kooi-group/', 'https://www.rug.nl/staff/b.j.kooi/', 'https://www.rug.nl/research/zernike/people/Kooi-1-WEB.jpg'],
                ['Palasantzas', 'Surface Interactions and Nanostructures', 'https://www.rug.nl/research/zernike/nanostructured-materials-and-interfaces/palasantzas-group/', 'https://www.rug.nl/staff/g.palasantzas/', 'https://www.rug.nl/research/zernike/people/Palasantzas-1-WEB.jpg'],
            ],
        ],
        [
            'title' => 'Nanostructures of Functional Oxides',
            'description' => "We investigate materials that display physical phenomena and responses of relevance for electronic applications.\n\nWe are particularly interested in ferroelectric, piezoelectric, thermoelectric, ferromagnetic or multiferroic oxides. Our approach consists of exploiting the symmetry-property relationships by achieving a high control of the materials synthesis, combined with a detailed nanoscale structural and physical characterization.",
            'photo' => 'https://www.rug.nl/research/zernike/research/topic_nfo.jpg',
            'units' => [
                ['Noheda', 'Noheda Group', 'https://www.rug.nl/research/zernike/nanostructures-of-functional-oxides/nanostructures-of-functional-oxides', 'https://www.rug.nl/research/zernike/nanostructures-of-functional-oxides/contact', 'https://www.rug.nl/research/zernike/people/noheda-1-web-new.jpg'],
                ['Blake', 'Blake Group', 'https://www.rug.nl/research/zernike/nanostructures-of-functional-oxides/blake-group/', 'https://www.rug.nl/research/zernike/nanostructures-of-functional-oxides/blake-group/contact', 'https://www.rug.nl/research/zernike/people/blake-1-web-new.jpg'],
            ],
        ],
        [
            'title' => 'Photophysics and Opto-electronics',
            'description' => "Our group aims to develop novel materials for solar cell & microelectronics applications. The materials we work on have in common that they are solution processable. This property holds the promise of cheap production methods with a low energy demand. Our research focusses on a number of subjects:\n\n- The properties of organic semiconductors and organic/organic interfaces and their application in optoelectronic devices\n- Physical and optoelectronic properties of carbon nanotubes and hybrid systems\n- Fabrication of hybrid optoelectronic devices composed by inorganic nanocrystals and organic molecules",
            'photo' => 'https://www.rug.nl/research/zernike/photophysics-and-opto-electronics/images/PbS-CdS_Coreshell_quantumdots.png',
            'units' => [
                ['Loi', 'Materials for Solar Cell & Microelectronics Applications', 'https://www.rug.nl/research/zernike/photophysics-and-opto-electronics/loi-group/', 'https://www.rug.nl/staff/m.a.loi/', 'https://www.rug.nl/research/zernike/photophysics-and-opto-electronics/loi-group/images/employees/Maria_Loi_2.jpg'],
                ['Koster', 'Device Physics of Organic Semiconductors', 'https://www.rug.nl/research/zernike/photophysics-and-opto-electronics/koster-group/', 'https://www.rug.nl/staff/l.j.a.koster/', 'https://www.rug.nl/research/zernike/photophysics-and-opto-electronics/images/Jan_Anton_Koster_2.jpg'],
            ],
        ],
        [
            'title' => 'Physics of Nanodevices',
            'description' => 'We explore new physical phenomena that occur in electronic and opto-electronic device structures with nanoscale dimensions. The dynamics of such devices is often quantum mechanical in nature, but much richer than the dynamics of isolated atoms due to interactions with the solid-state environment. Our research investigates this quantum dynamics, and aims to apply it for new device functionalities.',
            'photo' => 'https://www.rug.nl/research/zernike/images/034_tbjh_0619_800x386px.jpg',
            'units' => [
                ['van Wees', 'Magnon Spintronics in Magnetic Insulators', 'https://www.rug.nl/research/zernike/physics-of-nanodevices/van-wees-group/', 'https://www.rug.nl/staff/b.j.van.wees/', 'https://www.rug.nl/research/zernike/people/VanWees-1-WEB.jpg'],
                ['van der Wal', 'Physics of Quantum Devices', 'https://www.rug.nl/research/zernike/physics-of-nanodevices/van-der-wal-group/', 'https://www.rug.nl/staff/c.h.van.der.wal/', 'https://www.rug.nl/research/zernike/people/VanDerWal-1-WEB.jpg'],
                ['Banerjee', 'Spintronics in Functional Materials', 'https://www.rug.nl/research/zernike/physics-of-nanodevices/banerjee-group/', 'https://www.rug.nl/staff/t.banerjee/', 'https://www.rug.nl/research/zernike/people/Banjeree-1-WEB.jpg'],
                ['Marcos Diniz Guimaraes', 'Opto-Spintronics of Nanostructures', 'https://www.rug.nl/research/zernike/physics-of-nanodevices/guimaraes-group/', 'https://www.rug.nl/staff/m.h.guimaraes/', 'https://professorfrancken.nl/uploads/images/research-groups/marcos.jpg'],
            ],
        ],
        [
            'title' => 'Quantum Interactions and Structural Dynamics',
            'description' => "Our research efforts focus on the ultrafast processes induced by energetic particle and photon interactions with matter ranging from single atoms and (bio-)molecules via clusters to surfaces. We aim at unveiling\n\n- the primary electron dynamics occurring on femtosecond timescales and\n- the subsequent structural and functional changes.\n\nThe knowledge obtained is applied in fields such as nanolithography, advanced material science, space research, mass spectrometry and radiotherapy.\n\nThe research has a strong instrumentation development component. For our experiments with ions we operate the ZERNIKE-LEIF facility centered around our highly-charged ion source. The photon work is performed at synchrotrons or Free Electron Lasers.",
            'photo' => 'https://www.rug.nl/research/zernike/research/topic_qisd.png',
            'units' => [
                ['Hoekstra', 'Experimental Atomic Physics', 'https://www.rug.nl/research/zernike/quantum-interactions-and-structural-dynamics/hoekstra-group/', 'https://www.rug.nl/staff/r.a.hoekstra/', 'https://www.rug.nl/research/zernike/people/hoekstra-1-web-new.jpg'],
                ['Schlathölter', 'Gas Phase Biomolecules and Energetic Interactions', 'https://www.rug.nl/research/zernike/quantum-interactions-and-structural-dynamics/schlatholter-group/', 'https://www.rug.nl/staff/t.a.schlatholter/', 'https://www.rug.nl/research/zernike/people/schlatholter-1-web-new.jpg'],
            ],
        ],
    ];

    private const INTRODUCTION = "Most research groups of engineering physics are part of the [Zernike Institute for Advanced Materials](https://www.rug.nl/research/zernike/). It got its name in 2007, but grew out of the Materials Science Centre that started back in 1970. Physicists and chemists, and more and more biologists, theoreticians and experimentalists work closely together, from modelling and synthesis to the properties and functions of devices.\n\nLooking for a group for your bachelor or master thesis? Below you find what each group works on, and who to contact.";

    /**
     * Run the migrations.
     */
    public function up() : void
    {
        Schema::create('study_research_groups', function (Blueprint $table) : void {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('source_content')->nullable();
            $table->text('compiled_content')->nullable();

            // A photo is either uploaded or, for the groups that came from the old website, a url
            $table->integer('photo_media_id')->unsigned()->nullable();
            $table->foreign('photo_media_id')->references('id')->on('media')->nullOnDelete();
            $table->string('photo_url')->nullable();

            $table->timestamps();
        });

        Schema::create('study_research_units', function (Blueprint $table) : void {
            $table->id();

            $table->foreignId('research_group_id')->constrained('study_research_groups')->cascadeOnDelete();

            $table->string('name');
            $table->string('title');
            $table->string('website_url')->nullable();
            $table->string('contact_url')->nullable();

            $table->integer('photo_media_id')->unsigned()->nullable();
            $table->foreign('photo_media_id')->references('id')->on('media')->nullOnDelete();
            $table->string('photo_url')->nullable();

            $table->timestamps();
        });

        $compiler = new ContentCompiler();
        $now = now();

        foreach (self::GROUPS as $group) {
            $markdown = $compiler->content($group['description']);

            $groupId = DB::table('study_research_groups')->insertGetId([
                'title' => $group['title'],
                'slug' => Str::slug($group['title']),
                'source_content' => $markdown->originalMarkdown(),
                'compiled_content' => $markdown->compiledContent(),
                'photo_url' => $group['photo'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($group['units'] as [$name, $title, $website, $contact, $photo]) {
                DB::table('study_research_units')->insert([
                    'research_group_id' => $groupId,
                    'name' => $name,
                    'title' => $title,
                    'website_url' => $website,
                    'contact_url' => $contact,
                    'photo_url' => $photo,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // The introduction of the research groups page is a page of its own, so that it
        // can be changed from the admin
        $markdown = $compiler->content(self::INTRODUCTION);
        DB::table('francken_pages')->insert([
            'slug' => 'study/research-groups',
            'title' => 'Research Groups',
            'description' => 'Research groups of engineering physics, what they work on and who to contact.',
            'source_content' => $markdown->originalMarkdown(),
            'compiled_content' => $markdown->compiledContent(),
            'is_published' => false,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down() : void
    {
        DB::table('francken_pages')->where('slug', 'study/research-groups')->delete();
        Schema::dropIfExists('study_research_units');
        Schema::dropIfExists('study_research_groups');
    }
}
