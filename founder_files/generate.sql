use YeastDatabase;
insert into marker (name)
values ('KanMX'),
('NatMX');
insert into plasmid (name)
values ('PFA6A-KanMX'),
('PFA6A-NatMX');
insert into oligo (name,sequence)
values ('ML-1','ACGTACGTT'),
('ML-2','AAAATTTCCGGG');
insert into promoter (name)
values ('Pnmt1'),
('Pnmt41'),
('Pnmt81');
insert into tag (name,color)
values ('mCherry','red'),
('GFP','green'),
('eGFP','green'),
('ENVY','green'),
('GST','');
insert into strain_source_tag (name)
values ('Custom'),
('Mating'),
('MolBiol'),
('Plasmid');
